<?php

use App\Tarea;
use App\Subtareas;
use Illuminate\Database\Seeder;

class SubtareasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subtareas::truncate();
        $tareas = [
            [
                'estado_id' => 8,
                'data' => json_encode([
                    ['titulo' => 'Pago de tasas y timbres']
                ])
            ],
            [
                'estado_id' => 13,
                'data' => json_encode([
                    ['titulo' => 'Sacar copias y abrir expediente administrativo (Archivo físico)'],
                    ['titulo' => 'Realizar cartas de entrega y sobres de las solicitudes originales a los clientes'],
                    ['titulo' => 'Notificar al cliente de retirar el expediente']
                ])
            ],
            [
                'estado_id' => 20,
                'data' => json_encode([
                    ['titulo' => 'Notificar al cliente']
                ])
            ],
        ];
        foreach($tareas as $tarea){
            Subtareas::create($tarea);
        }
    }
}

/*
    $tareas = [
            [
                'estado_id'             => 5,
                'data'                  => json_encode([
                    ['titulo' => 'Pago timbre']
                ])
            ],
            [
                'estado_id'             => 9,
                'data'                  => json_encode([
                    ['titulo' => 'Enviar correo electronico dando aviso de los resultados solicitando pago de la siguiente fase']
                ])
            ],
            [
                'estado_id'             => 11,
                'data'                  => json_encode([
                    ['titulo' => 'Realizacion de anexos de solicitudes de marca'],
                    ['titulo' => 'Enviar a pagar tasas y timbres']
                ])
            ],
            [
                'estado_id'             => 15,
                'data'                  => json_encode([
                    ['titulo' => 'Sacar copias y abrir expediente administrativo (Archivo físico)'],
                    ['titulo' => 'Realizar cartas de entrega y sobres de las solicitudes originales a los clientes'],
                    ['titulo' => 'Notificar al cliente que venga a retirar el expediente']
                ])
            ],
            [
                'estado_id'             => 19,
                'data'                  => json_encode([
                    ['titulo' => 'Notificar al cliente'],
                    ['titulo' => 'Realizar deposito'],
                    ['titulo' => 'Enviar depósito']
                ])
            ],
            [
                'estado_id'             => 20,
                'data'                  => json_encode([
                    ['titulo' => 'Editar la marca y actualizarle el número de registro y fecha de vigencia']
                ])
            ],
            [
                'estado_id'             => 22,
                'data'                  => json_encode([
                    ['titulo' => 'Notificar al cliente que retire su certificado']
                ])
            ]
        ];
 */
