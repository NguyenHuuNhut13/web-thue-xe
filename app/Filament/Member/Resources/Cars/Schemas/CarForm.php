<?php

namespace App\Filament\Member\Resources\Cars\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;

class CarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Tên xe')
                    ->placeholder('Ví dụ: Toyota Fortuner 2023')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state))),
                
                TextInput::make('slug')
                    ->label('Đường dẫn tĩnh (Slug)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('Viết liền không dấu, ví dụ: toyota-fortuner-2023'),

                Select::make('brand')
                    ->label('Hãng xe')
                    ->options([
                        'Toyota' => 'Toyota',
                        'Honda' => 'Honda',
                        'VinFast' => 'VinFast',
                        'Ford' => 'Ford',
                        'Hyundai' => 'Hyundai',
                        'Kia' => 'Kia',
                        'Mazda' => 'Mazda',
                        'Mitsubishi' => 'Mitsubishi',
                        'Mercedes-Benz' => 'Mercedes-Benz',
                        'BMW' => 'BMW',
                    ])
                    ->required()
                    ->searchable(),

                TextInput::make('model')
                    ->label('Dòng xe')
                    ->placeholder('Ví dụ: Fortuner, Civic, VF8')
                    ->required(),

                TextInput::make('year')
                    ->label('Năm sản xuất')
                    ->required()
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue(date('Y') + 1),

                Select::make('fuel_type')
                    ->label('Loại nhiên liệu')
                    ->options([
                        'gasoline' => 'Xăng',
                        'diesel' => 'Dầu (Diesel)',
                        'electric' => 'Điện',
                        'hybrid' => 'Hybrid',
                    ])
                    ->required(),

                Select::make('transmission')
                    ->label('Hộp số')
                    ->options([
                        'automatic' => 'Số tự động',
                        'manual' => 'Số sàn',
                    ])
                    ->required(),

                Select::make('seats')
                    ->label('Số chỗ ngồi')
                    ->options([
                        4 => '4 chỗ',
                        5 => '5 chỗ',
                        7 => '7 chỗ',
                        9 => '9 chỗ',
                        16 => '16 chỗ',
                    ])
                    ->required(),

                TextInput::make('price_per_day')
                    ->label('Giá thuê theo ngày (VNĐ)')
                    ->required()
                    ->numeric()
                    ->prefix('VNĐ')
                    ->placeholder('Ví dụ: 800000'),

                TextInput::make('location')
                    ->label('Địa chỉ vị trí xe')
                    ->placeholder('Ví dụ: 222 Lê Văn Sỹ, Phường 14, Quận 3, TP.HCM')
                    ->columnSpanFull(),

                Section::make('Bản đồ & Định vị vị trí xe')
                    ->description('Xác định vị trí GPS chính xác để hiển thị xe của bạn trên bản đồ tìm kiếm.')
                    ->schema([
                        Placeholder::make('map_picker')
                            ->label('')
                            ->view('filament.forms.components.map-picker')
                            ->columnSpanFull(),

                        TextInput::make('latitude')
                            ->label('Vĩ độ (Latitude)')
                            ->numeric()
                            ->readOnly()
                            ->placeholder('Ví dụ: 10.7904')
                            ->helperText('Được điền tự động khi chọn vị trí trên bản đồ.'),

                        TextInput::make('longitude')
                            ->label('Kinh độ (Longitude)')
                            ->numeric()
                            ->readOnly()
                            ->placeholder('Ví dụ: 106.6713')
                            ->helperText('Được điền tự động khi chọn vị trí trên bản đồ.'),
                    ])
                    ->columns(2)
                    ->columnSpan(1)
                    ->extraAttributes(['class' => 'h-full']),

                Section::make('Hình ảnh xe')
                    ->description('Tải lên hình ảnh chi tiết của xe (ngoại thất, nội thất). Kéo thả để sắp xếp lại.')
                    ->schema([
                        FileUpload::make('images')
                            ->label('')
                            ->multiple()
                            ->image()
                            ->disk('public')
                            ->directory('cars')
                            ->reorderable(),
                    ])
                    ->columnSpan(1)
                    ->extraAttributes(['class' => 'h-full']),

                Textarea::make('description')
                    ->label('Mô tả chi tiết trang bị & tình trạng xe')
                    ->placeholder('Mô tả chi tiết về xe, các tính năng đặc biệt (cảm biến, camera hành trình, định vị GPS, v.v.)...')
                    ->columnSpanFull(),

                Toggle::make('has_driver')
                    ->label('Cho thuê xe có kèm tài xế')
                    ->default(false)
                    ->helperText('Bật nếu xe này bạn cho thuê có tài xế kèm theo, tắt nếu là xe tự lái.'),

                TextInput::make('status')
                    ->label('Trạng thái duyệt')
                    ->default('pending')
                    ->disabled()
                    ->dehydrated()
                    ->helperText('Xe mới đăng sẽ chờ Admin duyệt trước khi hiển thị công khai.'),
            ]);
    }
}
