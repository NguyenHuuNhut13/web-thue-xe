<?php

namespace App\Filament\Resources\Blogs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;

class BlogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Tác giả')
                    ->relationship('author', 'name')
                    ->required()
                    ->default(auth()->id()),

                TextInput::make('title')
                    ->label('Tiêu đề bài viết')
                    ->placeholder('Ví dụ: Kinh nghiệm đi Vũng Tàu tự lái xe')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->label('Đường dẫn tĩnh (Slug)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('kinh-nghiem-di-vung-tau-tu-lai-xe'),

                Textarea::make('summary')
                    ->label('Tóm tắt ngắn')
                    ->placeholder('Tóm tắt ngắn gọn nội dung bài viết hiển thị ở trang danh sách tin tức...')
                    ->columnSpanFull(),

                RichEditor::make('content')
                    ->label('Nội dung chi tiết')
                    ->placeholder('Soạn thảo nội dung bài viết chi tiết tại đây...')
                    ->required()
                    ->columnSpanFull(),

                FileUpload::make('image')
                    ->label('Ảnh đại diện bài viết')
                    ->image()
                    ->disk('public')
                    ->directory('blogs')
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Bản nháp (Draft)',
                        'published' => 'Xuất bản (Published)',
                    ])
                    ->required()
                    ->default('draft'),
            ]);
    }
}
