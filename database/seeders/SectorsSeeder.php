<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SectorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {/* Urbanos */
        DB::table('sectors')->insert([
            ['name' => 'Agustín Ross', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Alto Las Cumbres', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Alto Lafquén', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Alto Pucalán I', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Alto Pucalán II', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Alto Pucará', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Arauco', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Atardecer', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bicentenario', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bosques del Mar', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cuatro Colinas', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'El Badillo', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'El Bajo Estación', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'El Descanso', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'El Llano', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'El Olimpo', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Infiernillo', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'La Alborada', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'La Caleta', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'La Paz', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Laguna El Ancho', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Las Américas', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Las Araucarias', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Las Palmeras', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Las Proteas', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Los Andes', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Los Cipreses', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Los Cisnes del Ancho', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Los Jardines', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Los Lagos', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Los Navegantes', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Los Nogales', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Los Poetas', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Los Profesores', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Los Robles', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lomas del Valle', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mar Azul', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nueva Ilusión', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pichilemu Centro', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Playa Hermosa', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rayito de Sol', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Reina del Mar', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Padre Hurtado', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pavez Polanco', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pueblo de Viudas', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Puente Negro', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Punta del Sol', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'San Andrés', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'San Antonio', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'San Francisco', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'San Jorge', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Santa Gemita', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Santa Teresita', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Villa Pichilemu', 'type' => 'urbano', 'created_at' => now(), 'updated_at' => now()],
        ]);

        /* Rurales */
        DB::table('sectors')->insert([
            ['name' => 'Alto Colorado', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Alto Ramírez', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Barrancas', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Buenos Aires', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cáhuil', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cardonal de Panilonco', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Catrianca', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ciruelos', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cóguil', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'El Boldo', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'El Maqui', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'El Pangal', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Espinillo', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Las Comillas', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'La Aguada', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'La Palmilla', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'La Plaza', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'La Quebrada', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'La Villa', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lo Gallardo', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Los Curicanos', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nuevo Reino', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pañul', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Punta de Lobos', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rodeíllo', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tanumé', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Villa Esperanza', 'type' => 'rural', 'created_at' => now(), 'updated_at' => now()],
        ]);

        /* Otros */
        DB::table('sectors')->insert([
            ['name' => 'Fuera de la comuna', 'type' => 'other', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
