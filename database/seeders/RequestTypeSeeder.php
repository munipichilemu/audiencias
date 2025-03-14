<?php

namespace Database\Seeders;

use App\Models\RequestType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use function Ramsey\Uuid\v1;

class RequestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('request_types')->insert([
            ['name' => 'Laboral', 'description' => 'CV, empleo, servicios profesionales', 'color' => '{"key":"emerald","property":"--emerald-500","label":"Emerald","type":"rgb","value":"16, 185, 129"}'],
            ['name' => 'Personal', 'description' => 'Motivo no especificado o privado', 'color' => '{"key":"violet","property":"--violet-500","label":"Violet","type":"rgb","value":"139, 92, 246"}'],
            ['name' => 'Social', 'description' => 'Ayuda social, subsidios, emergencias', 'color' => '{"key":"blue","property":"--blue-500","label":"Blue","type":"rgb","value":"59, 130, 246"}'],
            ['name' => 'Proyectos/Iniciativas', 'description' => 'Presentación de proyectos públicos o privados', 'color' => '{"key":"lime","property":"--lime-500","label":"Lime","type":"rgb","value":"132, 204, 22"}'],
            ['name' => 'Permisos Comerciales', 'description' => 'Patentes, autorizaciones para negocios', 'color' => '{"key":"orange","property":"--orange-500","label":"Orange","type":"rgb","value":"249, 115, 22"}'],
            ['name' => 'Organizaciones', 'description' => 'Vecinales, gremios, asociaciones', 'color' => '{"key":"fuchsia","property":"--fuchsia-500","label":"Fuchsia","type":"rgb","value":"217, 70, 239"}'],
            ['name' => 'Reclamos', 'description' => 'Quejas sobre servicios o atención municipal', 'color' => '{"key":"red","property":"--red-500","label":"Red","type":"rgb","value":"239, 68, 68"}'],
            ['name' => 'Servicios/Ofertas', 'description' => 'Propuestas comerciales o de lobby', 'color' => '{"key":"cyan","property":"--cyan-500","label":"Cyan","type":"rgb","value":"6, 182, 212"}'],
            ['name' => 'Infraestructura', 'description' => 'Obras públicas, urbanismo, mantención', 'color' => '{"key":"zinc","property":"--zinc-500","label":"Zinc","type":"rgb","value":"113, 113, 122"}'],
            ['name' => 'Cultura', 'description' => 'Eventos culturales, talleres, patrimonio', 'color' => '{"key":"pink","property":"--pink-500","label":"Pink","type":"rgb","value":"236, 72, 153"}'],
            ['name' => 'Medio Ambiente', 'description' => 'Sostenibilidad, residuos, áreas verdes', 'color' => '{"key":"green","property":"--green-500","label":"Green","type":"rgb","value":"34, 197, 94"}'],
            ['name' => 'Educación', 'description' => 'Proyectos educativos, becas', 'color' => '{"key":"rose","property":"--rose-500","label":"Rose","type":"rgb","value":"244, 63, 94"}'],
            ['name' => 'Salud', 'description' => 'Consultas médicas, campañas sanitarias', 'color' => '{"key":"teal","property":"--teal-500","label":"Teal","type":"rgb","value":"20, 184, 166"}'],
            ['name' => 'Seguridad', 'description' => 'Delincuencia, tránsito, iluminación', 'color' => '{"key":"yellow","property":"--yellow-500","label":"Yellow","type":"rgb","value":"234, 179, 8"}'],
            ['name' => 'Turismo', 'description' => 'Propuestas turísticas, promoción', 'color' => '{"key":"sky","property":"--sky-500","label":"Sky","type":"rgb","value":"14, 165, 233"}'],
            ['name' => 'Eventos', 'description' => 'Permisos para actividades públicas', 'color' => '{"key":"slate","property":"--slate-500","label":"Slate","type":"rgb","value":"100, 116, 139"}'],
            ['name' => 'Otros', 'description' => 'Casos no cubiertos por las opciones anteriores', 'color' => '{"key":"indigo","property":"--indigo-500","label":"Indigo","type":"rgb","value":"99, 102, 241"}'],
        ]);
    }
}
