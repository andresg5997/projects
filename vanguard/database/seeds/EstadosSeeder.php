<?php

use Illuminate\Database\Seeder;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Estado::truncate();

        $estados = [
            [ // ID 1
                'nombre' => 'Elaboración de oferta',
                'titulo_tarea' => 'Preparar y enviar la oferta',
                'estado_posterior' =>  '2',
                'requisitos' => json_encode([0 => ['nombre' => 'archivo', 'tipo' => 'file']]),
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ],
            [ // ID 2
                'nombre' => 'Oferta enviada',
                'titulo_tarea' => 'Hacer seguimiento de la oferta',
                'estado_posterior' =>  '3,4',
                'requisitos' => json_encode([0 => ['nombre' => 'archivo', 'tipo' => 'file']]),
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ],
            [ // ID 3
                'nombre' => 'Oferta aprobada, pendiente recaudos',
                'titulo_tarea' => 'Buscar recaudos para la búsqueda de antecedentes',
                'estado_posterior' =>  '5',
                'requisitos' => json_encode([0 => ['nombre' => 'archivo', 'tipo' => 'file']]),
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ],
            [ // ID 4
                'nombre' => 'Oferta rechazada',
                'titulo_tarea' => '',
                'estado_posterior' =>  '',
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ],
            [ // ID 5
                'nombre' => 'Pendiente búsqueda de antecedentes',
                'titulo_tarea' => '-hacer búsqueda de antecedentes',
                'estado_posterior' =>  '6,7',
                'requisitos' => json_encode([0 => ['nombre' => 'archivo', 'tipo' => 'file']]),
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ],
            [ // ID 6
                'nombre' => 'Antecedentes favorables',
                'titulo_tarea' => 'Contactar al cliente y solicitar pago',
                'estado_posterior' =>  '8',
                'requisitos' => json_encode([0 => ['nombre' => 'soporte_aprobacion', 'tipo' => 'file']]),
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ],
            [ // ID 7
                'nombre' => 'Antecedentes desfavorables',
                'titulo_tarea' => '',
                'estado_posterior' =>  '',
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ],
            [ // ID 8
                'nombre' => 'Solicitud pendiente',
                'titulo_tarea' => 'Armar archivos de anexos',
                'estado_posterior' =>  '9',
                'requisitos' => json_encode([0 => ['nombre' => 'anexo', 'tipo' => 'file']]),
                // Subtareas: Pago de tasas y timbres
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ],
            [ // ID 9
                'nombre' => 'Por revisión de doctores',
                'titulo_tarea' => 'Revisar abogados',
                'estado_posterior' =>  '10',
                'requisitos' => json_encode([0 => ['nombre' => 'notas', 'tipo' => 'text']]),
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ],
            [ // ID 10
                'nombre' => 'Pendiente solicitud de marca',
                'titulo_tarea' => 'Armar expediente de solicitud de marca',
                'estado_posterior' =>  '11',
                'requisitos' => json_encode([0 => ['nombre' => 'notas', 'tipo' => 'text']]),
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ],
            [ // ID 11
                'nombre' => 'Pendiente firma del cliente',
                'titulo_tarea' => 'Coordinar con el cliente la firma',
                'estado_posterior' =>  '12',
                'requisitos' => json_encode([0 => ['nombre' => 'soporte_envio', 'tipo' => 'file']]),
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ],
            [ // ID 12
                'nombre' => 'Expendiente por envío',
                'titulo_tarea' => 'Enviar expediente',
                'estado_posterior' =>  '13',
                'requisitos' => json_encode([0 => ['nombre' => 'soporte_envio', 'tipo' => 'file']]),
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ],
            [ // ID 13
                'nombre' => 'Solicitud de marca enviada',
                'titulo_tarea' => 'Armar expediente administrativo',
                'estado_posterior' =>  '14',
                'requisitos' => json_encode([0 => ['nombre' => 'soporte_recepcion', 'tipo' => 'file']]),
                // Subtareas: Sacar copias y abrir expediente administrativo (Archivo físico)
                // Realizar cartas de entrega y sobres de las solicitudes originales a los clientes
                // Notificar al cliente de retirar el expediente
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ],
            [ // ID 14
                'nombre' => 'En espera de respuesta SAPI',
                'titulo_tarea' => 'Seguimiento de la solicitud',
                'estado_posterior' =>  '15,16,17',
                'requisitos' => json_encode([0 => ['nombre' => 'soporte_envio', 'tipo' => 'file']]),
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ],
           [ // ID 15
                'nombre' => 'Marca con orden de publicación',
                'titulo_tarea' => 'Notificar al cliente y depositar',
                'estado_posterior' =>  '19',
                'requisitos' => json_encode([0 => ['nombre' => 'soporte_envio', 'tipo' => 'file']]),
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ],
            [ // ID 16
                'nombre' => 'En subsanación de solicitud',
                'titulo_tarea' => 'Subsanar la solicitud',
                'estado_posterior' =>  '15,16,17',
                'requisitos' => json_encode([0 => ['nombre' => 'solicitud', 'tipo' => 'file']]),
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ],
            [ // ID 17
                'nombre' => 'Negada',
                'titulo_tarea' => '',
                'estado_posterior' =>  '',
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ],
            [ // ID 18
                'nombre' => 'Subsanación enviada',
                'titulo_tarea' => 'Enviar expediente',
                'estado_posterior' =>  '19',
                'requisitos' => json_encode([0 => ['nombre' => 'soporte_envio', 'tipo' => 'file']]),
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ],
            [ // ID 19
                'nombre' => 'Pendiente publicación en prensa',
                'titulo_tarea' => 'Seguimiento de publicación',
                'estado_posterior' =>  '20',
                'requisitos' => json_encode([0 => ['nombre' => 'soporte', 'tipo' => 'file']]),
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ],
            [ // ID 20
                'nombre' => 'Marca concedida',
                'titulo_tarea' => 'Actualizar marca en el sistema',
                'estado_posterior' =>  '13',
                'requisitos' => json_encode([0 => ['nombre' => 'soporte_envio', 'tipo' => 'file']]),
                // Subtareas: Notificar al cliente
                'proceso_id' => mt_rand(1,2),
                'tiempo_seguimiento' => mt_rand(10,30)
            ]
        ];

        foreach($estados as $estado){
            App\Estado::create($estado);
        }
    }
}

// Viejos estados
/*
        $estados =
        [
            [ // ID 1
                'nombre'                => 'Elaboración de oferta',
                'estado_posterior'      => '2',
                'requisitos'            => ''
            ],
            [ // ID 2
                'nombre'                => 'Envío de oferta de servicio',
                'estado_posterior'      => '3,4',
                'requisitos'            => json_encode([
                                            'archivo' => 'file'
                                        ]),
                'tiempo_seguimiento'    => '21'
            ],
            [ // ID 3
                'nombre'            => 'Oferta aprobada',
                'estado_posterior'  => '5',
                'requisitos'        => json_encode([ 'codigo' => 'text']),
                // 'estado_muerte'     => 4
            ],
            [ // ID 4
                'nombre'            => 'Oferta rechazada',
                'estado_posterior'  => '',
                'requisitos'        => json_encode(['codigo' => 'text']),
                // 'estado_muerte'     => 4
            ],
            [ // ID 5
                // Debe llevar subtarea: Pago timbre para asignar a persona.
                'nombre'                => 'Recaudos para búsquedas',
                'estado_posterior'      => '6',
                'requisitos'            => json_encode([
                                            'rif' => 'file',
                                            'comprobante_pago' => 'file',
                                            'logo'  => 'file',
                                            'pago_timbre' => 'file'
                                        ]),
                'tiempo_seguimiento'    => '21'
            ],
            [ // ID 6
                'nombre'                => 'Búsqueda de antecedentes',
                'estado_posterior'      => '7,8',
                'requisitos'            => json_encode(['notas' => 'text'])
            ],
            [ // ID 7
                'nombre'                => 'Antecedentes favorables',
                'estado_posterior'      => '9',
                'requisitos'            => json_encode(['archivo' => 'file', 'notas' => 'text'])
            ],
            [   // ID 8
                'nombre'                => 'Antecedentes desfavorables',
                'estado_posterior'      => '10',
                'requisitos'            => json_encode(['archivo' => 'file', 'notas' => 'text'])
            ],
            [ // ID 9
                // Subtarea: Enviar correo electronico dando aviso de los resultados solicitando pago de la siguiente fase
                // Realizar tres llamadas de seguimiento, una semanal para cobrar las solicitudes
                'nombre'            => 'Oferta de solicitud de marca aprobada',
                'estado_posterior'  => '11',
                'requisitos'        => json_encode([ 'codigo' => 'text']),
                // 'estado_muerte'     => 4
            ],
            [ // ID 10
                'nombre'            => 'Oferta de solicitud de marca rechazada',
                'estado_posterior'  => '12',
                'requisitos'        => json_encode(['codigo' => 'text'])
            ],
            [ // ID 11
                // Subtareas: Realizacion de anexos de solicitudes de marca
                // Enviar a pagar tasas y timbres
                'nombre'            => 'Recaudos para solicitud',
                'estado_posterior'  => '14',
                'requisitos'        => json_encode(['anexo' => 'file', 'nota' => 'text'])
            ],
            [ // ID 12
                'nombre'            => 'Revisado por doctores',
                'estado_posterior'  => '13',
                'requisitos'        => json_encode(['notas' => 'text'])
            ],
            [ // ID 13
                'nombre'            => 'Realización de solicitud de marca',
                'estado_posterior'  => '14',
                'requisitos'        => json_encode(['notas' => 'text'])
            ],
            [ // ID 14
                'nombre'            => 'Firma del cliente',
                'estado_posterior'  => '15',
                'requisitos'        => json_encode(['notas' => 'text'])
            ],
            [ // ID 15
                // Tareas:
                // Sacar copias, abrir expediente administrativo (archivo fisico),
                // realizar cartas de entrega y sobres de las solicitudes originales a los clientes
                // Notificar al cliente que venga a buscar su asunto
                'nombre'            => 'Solicitud de marca enviada',
                'estado_posterior'  => '16,19',
                'requisitos'        => json_encode(['notas' => 'text'])
            ],
            [ // ID 16
                'nombre'            => 'Subsanar la solicitud',
                'estado_posterior'  => '17',
                'requisitos'        => json_encode(['notas' => 'text', 'archivo' => 'file'])
            ],
            [ // ID 17
                'nombre'            => 'Subsanación de solicitud enviada',
                'estado_posterior'  => '18',
                'requisitos'        => json_encode(['notas' => 'text', 'archivo' => 'file'])
            ],
            [ // ID 18
                'nombre'            => 'Marca concedida',
                'estado_posterior'  => '20',
                'requisitos'        => json_encode(['notas' => 'text'])
            ],
            [ // ID 19
                // Subtareas: Notificar al cliente
                // Realizar deposito
                // Enviar deposito
                'nombre'            => 'Publicación en prensa',
                'estado_posterior'  => '18',
                'requisitos'        => json_encode(['notas' => 'text', 'recibo' => 'file'])
            ],
            [ // ID 20
                // Tareas: Nro Registro, fecha de concesion y fecha de vigencia
                'nombre'            => 'Concesión de marca',
                'estado_posterior'  => '21',
                'requisitos'        => json_encode(['nro_registro' => 'text', 'fecha_concesion' => 'date', 'fecha_vigencia' => 'date'])
            ],
            [ // ID 21
                'nombre'            => 'Certificado de registro',
                'estado_posterior'  => '22',
                'requisitos'        => json_encode(['archivo_registro' => 'file'])
            ],
            [ // ID 22
                // Tareas: Notificar al cliente
                // Retiro del certificado por el cliente
                'nombre'            => 'Cierre de expediente',
                'estado_posterior'  => '',
                'requisitos'        => json_encode(['fecha_retiro' => 'date'])
            ]
        ];
 */
