<?php

namespace Modules;


use App\Classes\ExtendedModel;

/**
 * Class TaskItem
 * Quiero convertir esta clase en un "eloquent" de Laravel
 * @package Modules
 */
class TaskItem extends ExtendedModel
{
    /**
     * Con esto quedemos indicarle al eloquente que utilice la tabla "tk_tasks".
     * Esto es unido a la definición de tablas del módulo propiamente.
     * Está dividido solamente para mostrar cual es el prefijo y cual es la tabla en sí.
     * @var string
     */
    protected $table = "tk_" . "tasks";

}