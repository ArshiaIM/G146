<?php

namespace App\Filament\Company\Resources\ApprovalRequests;

use App\Filament\Company\Resources\ApprovalRequests\Pages\CreateApprovalRequest;
use App\Filament\Company\Resources\ApprovalRequests\Pages\EditApprovalRequest;
use App\Filament\Company\Resources\ApprovalRequests\Pages\ListApprovalRequests;
use App\Filament\Company\Resources\ApprovalRequests\Schemas\ApprovalRequestForm;
use App\Filament\Company\Resources\ApprovalRequests\Tables\ApprovalRequestsTable;
use App\Models\ApprovalRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ApprovalRequestResource extends Resource
{
    protected static ?string $model = ApprovalRequest::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;
    protected static ?string $navigationLabel = 'Approvals';
    protected static ?string $modelLabel      = 'Approval Request';
    protected static ?int    $navigationSort  = 12;

    // badge برای نشون دادن تعداد pending
    public static function getNavigationBadge(): ?string
    {
        $user  = auth()->user();
        $count = match($user?->role) {
            'battalion_admin' => ApprovalRequest::where('battalion_id', $user->battalion_id)
                ->where('status', 'approved_by_company')->count(),
            'company_admin'   => ApprovalRequest::where('company_id', $user->company_id)
                ->where('status', 'pending')->count(),
            'operator'        => ApprovalRequest::where('requested_by', $user->id)
                ->whereIn('status', ['pending', 'approved_by_company'])->count(),
            default           => 0,
        };

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return ApprovalRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApprovalRequestsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApprovalRequests::route('/'),
            'edit'  => EditApprovalRequest::route('/{record}/edit'),
        ];
    }
}
