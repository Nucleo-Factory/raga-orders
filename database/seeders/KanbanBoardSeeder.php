<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\KanbanBoard;
use App\Models\KanbanStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KanbanBoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todas las compañías
        $companies = Company::all();

        foreach ($companies as $company) {
            // Crear un tablero Kanban para órdenes de compra
            $board = KanbanBoard::create([
                'name' => 'Órdenes de Compra',
                'description' => 'Tablero Kanban para gestionar órdenes de compra',
                'company_id' => $company->id,
                'type' => 'purchase_orders',
                'is_active' => true,
            ]);

            // Crear estados para el tablero
            $statuses = [
                [
                    'name' => 'Por Procesar',
                    'slug' => 'por-procesar',
                    'description' => 'Órdenes que aún no han sido procesadas',
                    'position' => 1,
                    'color' => '#3498db',
                    'is_default' => true,
                    'is_final' => false,
                ],
                [
                    'name' => 'En Proceso',
                    'slug' => 'en-proceso',
                    'description' => 'Órdenes que están siendo procesadas',
                    'position' => 2,
                    'color' => '#f39c12',
                    'is_default' => false,
                    'is_final' => false,
                ],
                [
                    'name' => 'Enviadas',
                    'slug' => 'enviadas',
                    'description' => 'Órdenes que han sido enviadas',
                    'position' => 3,
                    'color' => '#2ecc71',
                    'is_default' => false,
                    'is_final' => false,
                ],
                [
                    'name' => 'Entregadas',
                    'slug' => 'entregadas',
                    'description' => 'Órdenes que han sido entregadas',
                    'position' => 4,
                    'color' => '#9b59b6',
                    'is_default' => false,
                    'is_final' => true,
                ],
                [
                    'name' => 'Canceladas',
                    'slug' => 'canceladas',
                    'description' => 'Órdenes que han sido canceladas',
                    'position' => 5,
                    'color' => '#e74c3c',
                    'is_default' => false,
                    'is_final' => true,
                ],
            ];

            foreach ($statuses as $status) {
                KanbanStatus::create([
                    'name' => $status['name'],
                    'slug' => $status['slug'],
                    'description' => $status['description'],
                    'kanban_board_id' => $board->id,
                    'position' => $status['position'],
                    'color' => $status['color'],
                    'is_default' => $status['is_default'],
                    'is_final' => $status['is_final'],
                ]);
            }
        }
    }
}
