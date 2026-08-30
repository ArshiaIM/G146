<?php

namespace App\Filament\Company\Resources\ApprovalRequests\Tables;

use App\Models\ApprovalRequest;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ApprovalRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) =>
                self::applyRoleFilter($query)
            )
            ->columns([
                TextColumn::make('requestedBy.name')
                    ->label('Requested By')
                    ->searchable(),

                TextColumn::make('model_type')
                    ->label('Model')
                    ->formatStateUsing(fn($state) => class_basename($state))
                    ->badge()
                    ->color('primary'),

                TextColumn::make('action')
                    ->label('Action')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'create' => 'success',
                        'update' => 'warning',
                        'delete' => 'danger',
                        default  => 'gray',
                    }),

                TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Pending'               => 'warning',
                        'Approved by Company'   => 'info',
                        'Approved by Battalion' => 'success',
                        'Rejected'              => 'danger',
                        'Executed'              => 'gray',
                        default                 => 'gray',
                    }),

                TextColumn::make('reason')
                    ->label('Reason')
                    ->limit(40)
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label('Requested At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'               => 'Pending',
                        'approved_by_company'   => 'Approved by Company',
                        'approved_by_battalion' => 'Approved by Battalion',
                        'rejected'              => 'Rejected',
                        'executed'              => 'Executed',
                    ]),

                SelectFilter::make('action')
                    ->label('Action')
                    ->options([
                        'create' => 'Create',
                        'update' => 'Update',
                        'delete' => 'Delete',
                    ]),
            ])
            ->recordActions([

                // تایید توسط فرمانده گروهان
                Action::make('approve_company')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(
                        fn(ApprovalRequest $record) =>
                        auth()->user()->role === 'company_admin'
                            && $record->status === 'pending'
                            && $record->requestedBy?->role !== 'company_admin'
                    )
                    ->requiresConfirmation()
                    ->action(function (ApprovalRequest $record) {
                        $record->update([
                            'status'                => 'approved_by_company',
                            'approved_by_company_id' => auth()->user()->id,
                            'company_approved_at'   => now(),
                        ]);

                        Notification::make()
                            ->title('Request approved by company')
                            ->success()
                            ->send();
                    }),

                // تایید توسط فرمانده گردان
                Action::make('approve_battalion')
                    ->label('Final Approve')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(
                        fn(ApprovalRequest $record) =>
                        auth()->user()->role === 'battalion_admin'
                            && $record->status === 'approved_by_company'
                    )
                    ->requiresConfirmation()
                    ->action(function (ApprovalRequest $record) {
                        $record->update([
                            'status'                  => 'approved_by_battalion',
                            'approved_by_battalion_id' => auth()->user()->id,
                            'battalion_approved_at'   => now(),
                        ]);

                        // اجرای عملیات
                        $record->execute();

                        Notification::make()
                            ->title('Request executed successfully')
                            ->success()
                            ->send();
                    }),

                // رد کردن
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(
                        fn(ApprovalRequest $record) =>
                        in_array(auth()->user()->role, ['company_admin', 'battalion_admin'])
                            && in_array($record->status, ['pending', 'approved_by_company'])
                    )
                    ->form([
                        Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (ApprovalRequest $record, array $data) {
                        $record->update([
                            'status'           => 'rejected',
                            'rejection_reason' => $data['rejection_reason'],
                        ]);

                        Notification::make()
                            ->title('Request rejected')
                            ->danger()
                            ->send();
                    }),

                // نمایش payload
                Action::make('view_details')
                    ->label('Details')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalHeading('Request Details')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalContent(function (ApprovalRequest $record) {

                        $payload = is_array($record->payload)
                            ? $record->payload
                            : (json_decode($record->payload ?? '{}', true) ?: []);

                        $original = is_array($record->original_data)
                            ? $record->original_data
                            : (json_decode($record->original_data ?? '{}', true) ?: []);

                        $allKeys = collect(array_unique([
                            ...array_keys($original),
                            ...array_keys($payload),
                        ]));

                        $changes = $allKeys->map(function ($key) use ($original, $payload) {

                            $oldExists = array_key_exists($key, $original);
                            $newExists = array_key_exists($key, $payload);

                            $oldValue = $original[$key] ?? null;
                            $newValue = $payload[$key] ?? null;

                            $oldJson = is_array($oldValue) || is_object($oldValue)
                                ? json_encode($oldValue, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
                                : $oldValue;

                            $newJson = is_array($newValue) || is_object($newValue)
                                ? json_encode($newValue, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
                                : $newValue;

                            if (!$oldExists && $newExists) {
                                $type = 'added';
                            } elseif ($oldExists && !$newExists) {
                                $type = 'removed';
                            } elseif ((string) $oldJson !== (string) $newJson) {
                                $type = 'changed';
                            } else {
                                $type = 'unchanged';
                            }

                            return [
                                'key' => $key,
                                'type' => $type,
                                'old' => $oldJson,
                                'new' => $newJson,
                            ];
                        })
                            ->filter(fn($change) => $change['type'] !== 'unchanged');

                        $statusColors = [
                            'pending' => '#f59e0b',
                            'approved_by_company' => '#3b82f6',
                            'approved_by_battalion' => '#10b981',
                            'rejected' => '#ef4444',
                            'executed' => '#6b7280',
                        ];

                        $statusLabels = [
                            'pending' => 'Pending',
                            'approved_by_company' => 'Approved by Company',
                            'approved_by_battalion' => 'Approved by Battalion',
                            'rejected' => 'Rejected',
                            'executed' => 'Executed',
                        ];

                        $statusColor = $statusColors[$record->status] ?? '#6b7280';
                        $statusLabel = $statusLabels[$record->status] ?? ucfirst($record->status);

                        $html = '
    <div dir="ltr" style="
        font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        color:#111827;
        max-height:70vh;
        overflow-y:auto;
        padding:4px;
    ">

        <!-- Header -->
        <div style="
            background:linear-gradient(135deg,#111827,#1f2937);
            color:white;
            border-radius:14px;
            padding:18px;
            margin-bottom:16px;
        ">
            <div style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                gap:12px;
            ">
                <div>
                    <div style="
                        font-size:11px;
                        color:#9ca3af;
                        text-transform:uppercase;
                        letter-spacing:.08em;
                        margin-bottom:5px;
                    ">
                        Approval Request
                    </div>

                    <div style="
                        font-size:20px;
                        font-weight:700;
                    ">
                        ' . e(class_basename($record->model_type)) . '
                        ' . ($record->model_id
                            ? '<span style="color:#9ca3af;">#' . e($record->model_id) . '</span>'
                            : '<span style="color:#9ca3af;">(New Record)</span>') . '
                    </div>
                </div>

                <div style="
                    background:' . $statusColor . ';
                    padding:6px 12px;
                    border-radius:999px;
                    font-size:12px;
                    font-weight:600;
                    white-space:nowrap;
                ">
                    ' . e($statusLabel) . '
                </div>
            </div>
        </div>

        <!-- Request Information -->
        <div style="
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:10px;
            margin-bottom:18px;
        ">

            <div style="
                border:1px solid #e5e7eb;
                border-radius:10px;
                padding:12px;
                background:#f9fafb;
            ">
                <div style="font-size:11px;color:#6b7280;margin-bottom:4px;">
                    MODEL
                </div>
                <div style="font-weight:600;">
                    ' . e(class_basename($record->model_type)) . '
                </div>
            </div>

            <div style="
                border:1px solid #e5e7eb;
                border-radius:10px;
                padding:12px;
                background:#f9fafb;
            ">
                <div style="font-size:11px;color:#6b7280;margin-bottom:4px;">
                    ACTION
                </div>
                <div style="
                    font-weight:700;
                    color:#374151;
                ">
                    ' . e(strtoupper($record->action)) . '
                </div>
            </div>

            <div style="
                border:1px solid #e5e7eb;
                border-radius:10px;
                padding:12px;
                background:#f9fafb;
            ">
                <div style="font-size:11px;color:#6b7280;margin-bottom:4px;">
                    CHANGES
                </div>
                <div style="font-weight:700;">
                    ' . $changes->count() . '
                </div>
            </div>

        </div>';

                        // <!-- Changes -->
                        $html .= '
        <div style="
            font-size:15px;
            font-weight:700;
            margin-bottom:10px;
            color:#111827;
        ">
            Changes
        </div>
    ';

                        if ($changes->isEmpty()) {

                            $html .= '
            <div style="
                padding:20px;
                text-align:center;
                border:1px dashed #d1d5db;
                border-radius:12px;
                color:#6b7280;
                background:#f9fafb;
            ">
                No changes detected.
            </div>
        ';
                        } else {

                            foreach ($changes as $change) {

                                $key = e($change['key']);

                                $old = $change['old'];
                                $new = $change['new'];

                                if ($old === null || $old === '') {
                                    $oldDisplay = '<span style="color:#9ca3af;">—</span>';
                                } else {
                                    $oldDisplay = e((string) $old);
                                }

                                if ($new === null || $new === '') {
                                    $newDisplay = '<span style="color:#9ca3af;">—</span>';
                                } else {
                                    $newDisplay = e((string) $new);
                                }

                                if ($change['type'] === 'added') {

                                    $badge = '
                    <span style="
                        background:#dcfce7;
                        color:#166534;
                        padding:3px 8px;
                        border-radius:999px;
                        font-size:10px;
                        font-weight:700;
                    ">
                        ADDED
                    </span>
                ';
                                } elseif ($change['type'] === 'removed') {

                                    $badge = '
                    <span style="
                        background:#fee2e2;
                        color:#991b1b;
                        padding:3px 8px;
                        border-radius:999px;
                        font-size:10px;
                        font-weight:700;
                    ">
                        REMOVED
                    </span>
                ';
                                } else {

                                    $badge = '
                    <span style="
                        background:#fef3c7;
                        color:#92400e;
                        padding:3px 8px;
                        border-radius:999px;
                        font-size:10px;
                        font-weight:700;
                    ">
                        CHANGED
                    </span>
                ';
                                }

                                $html .= '
                <div style="
                    border:1px solid #e5e7eb;
                    border-radius:12px;
                    overflow:hidden;
                    margin-bottom:10px;
                    background:white;
                ">

                    <!-- Field Header -->
                    <div style="
                        display:flex;
                        justify-content:space-between;
                        align-items:center;
                        padding:10px 12px;
                        background:#f9fafb;
                        border-bottom:1px solid #e5e7eb;
                    ">
                        <div style="
                            font-family:monospace;
                            font-size:13px;
                            font-weight:700;
                            color:#374151;
                        ">
                            ' . $key . '
                        </div>

                        ' . $badge . '
                    </div>

                    <!-- Values -->
                    <div style="
                        display:grid;
                        grid-template-columns:1fr 1fr;
                        gap:0;
                    ">

                        <!-- Old -->
                        <div style="
                            padding:12px;
                            background:#fff7f7;
                            border-right:1px solid #e5e7eb;
                        ">
                            <div style="
                                font-size:10px;
                                font-weight:700;
                                color:#b91c1c;
                                margin-bottom:6px;
                                text-transform:uppercase;
                            ">
                                Before
                            </div>

                            <pre style="
                                margin:0;
                                white-space:pre-wrap;
                                word-break:break-word;
                                font-family:monospace;
                                font-size:12px;
                                line-height:1.6;
                                color:#7f1d1d;
                            ">' . $oldDisplay . '</pre>
                        </div>

                        <!-- New -->
                        <div style="
                            padding:12px;
                            background:#f7fff9;
                        ">
                            <div style="
                                font-size:10px;
                                font-weight:700;
                                color:#15803d;
                                margin-bottom:6px;
                                text-transform:uppercase;
                            ">
                                After
                            </div>

                            <pre style="
                                margin:0;
                                white-space:pre-wrap;
                                word-break:break-word;
                                font-family:monospace;
                                font-size:12px;
                                line-height:1.6;
                                color:#166534;
                            ">' . $newDisplay . '</pre>
                        </div>

                    </div>
                </div>
            ';
                            }
                        }

                        $html .= '

        <!-- Raw Data -->
        <details style="
            margin-top:18px;
            border:1px solid #e5e7eb;
            border-radius:10px;
            overflow:hidden;
        ">
            <summary style="
                cursor:pointer;
                padding:12px;
                background:#f9fafb;
                font-size:13px;
                font-weight:600;
            ">
                View Raw JSON
            </summary>

            <pre style="
                margin:0;
                padding:12px;
                background:#111827;
                color:#e5e7eb;
                overflow:auto;
                max-height:300px;
                font-size:11px;
                line-height:1.6;
            ">' . e(json_encode([
                            'payload' => $payload,
                            'original_data' => $original,
                        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</pre>
        </details>

    </div>';

                        return new \Illuminate\Support\HtmlString($html);
                    })
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    private static function applyRoleFilter(Builder $query): Builder
    {
        $user = auth()->user();

        return match ($user->role) {
            'battalion_admin' => $query->where('battalion_id', $user->battalion_id),
            'company_admin'   => $query->where('company_id', $user->company_id),
            'operator'        => $query->where('requested_by', $user->id),
            default           => $query,
        };
    }
}
