<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManuelPersonelResource\Pages;
use App\Models\ManuelPersonel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\DB;

class ManuelPersonelResource extends Resource
{
   protected static ?string $model = ManuelPersonel::class;
   protected static ?string $navigationIcon = 'heroicon-o-users';
   protected static ?string $modelLabel = 'Manuel Personel';
   protected static ?string $pluralModelLabel = 'Manuel Personeller';

   public static function form(Form $form): Form
   {
       return $form
           ->schema([
               Forms\Components\Section::make()
                   ->schema([
                       Forms\Components\TextInput::make('personel_kodu')
                           ->required()
                           ->maxLength(50)
                           ->label('Personel Kodu')
                           ->afterStateUpdated(function ($state, Forms\Set $set) {
                               // TBL_PERSONEL_KARTLARI'nda kontrol yap
                               $exists = DB::table('TBL_PERSONEL_KARTLARI')
                                   ->where('PERSONEL_KODU', $state)
                                   ->exists();
                               
                               // ana_tabloda_var değerini güncelle
                               $set('ana_tabloda_var', $exists);

                               // Eğer personel varsa bilgilerini çek
                               if ($exists) {
                                   $personel = DB::table('TBL_PERSONEL_KARTLARI')
                                       ->where('PERSONEL_KODU', $state)
                                       ->first();
                                   
                                   // Diğer alanları otomatik doldur
                                   $set('personel_adsoyad', $personel->PERSONEL_ADSOYAD);
                                   $set('unvan_kod', $personel->UNVAN_KOD);
                               }
                           }),
                           
                       Forms\Components\TextInput::make('personel_adsoyad')
                           ->required()
                           ->maxLength(100)
                           ->label('Ad Soyad'),
                           
                       Forms\Components\TextInput::make('unvan_kod')
                           ->required()
                           ->maxLength(50)
                           ->label('Unvan Kodu'),
                           
                       Forms\Components\TagsInput::make('yetkili_depolar')
                           ->separator(',')
                           ->label('Yetkili Depolar')
                           ->helperText('Depo numaralarını virgülle ayırarak giriniz'),
                           
                       Forms\Components\Toggle::make('aktif')
                           ->default(true)
                           ->label('Aktif'),

                       Forms\Components\Toggle::make('ana_tabloda_var')
                           ->default(false)
                           ->label('Ana Tabloda Var')
                           ->disabled()  // Kullanıcı değiştiremesin
                   ])
           ]);
   }

   public static function table(Table $table): Table
   {
       return $table
           ->columns([
               Tables\Columns\TextColumn::make('personel_kodu')
                   ->searchable()
                   ->sortable()
                   ->label('Personel Kodu'),
                   
               Tables\Columns\TextColumn::make('personel_adsoyad')
                   ->searchable()
                   ->sortable()
                   ->label('Ad Soyad'),
                   
               Tables\Columns\TextColumn::make('unvan_kod')
                   ->searchable()
                   ->label('Unvan'),
                   
               Tables\Columns\TagsColumn::make('yetkili_depolar')
                   ->label('Yetkili Depolar'),
                   
               Tables\Columns\IconColumn::make('aktif')
                   ->boolean()
                   ->label('Aktif'),
                   
               Tables\Columns\IconColumn::make('ana_tabloda_var')
                   ->boolean()
                   ->label('Ana Tabloda')
           ])
           ->filters([
               SelectFilter::make('aktif')
                   ->options([
                       1 => 'Aktif',
                       0 => 'Pasif'
                   ])
                   ->label('Durum'),
               SelectFilter::make('ana_tabloda_var')
                   ->options([
                       1 => 'Ana Tabloda Var',
                       0 => 'Ana Tabloda Yok'
                   ])
                   ->label('Ana Tablo Durumu'),
           ])
           ->actions([
               Tables\Actions\EditAction::make(),
               Tables\Actions\DeleteAction::make(),
           ])
           ->bulkActions([
               Tables\Actions\BulkActionGroup::make([
                   Tables\Actions\DeleteBulkAction::make(),
               ]),
           ]);
   }
   
   public static function getRelations(): array
   {
       return [];
   }
   
   public static function getPages(): array
   {
       return [
           'index' => Pages\ListManuelPersonels::route('/'),
           'create' => Pages\CreateManuelPersonel::route('/create'),
           'edit' => Pages\EditManuelPersonel::route('/{record}/edit'),
       ];
   }
}