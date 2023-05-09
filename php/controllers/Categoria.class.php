<?php

namespace controllers;

use \classes\Respuesta;

class Categoria extends \models\Categoria
{
  public const CATEGORIA_INVALIDA = [
    'titulo' => 'Nombre de categoría inválida',
    'mensaje' => 'El nombre de la categoría solo debe contener letras y espacios.',
    'ambito' => 'general'
  ];

  public const CATEGORIA_REGISTRADA = [
    'titulo' => 'Nueva categoría añadida',
    'mensaje' => 'La categoría ha sido añadida con éxito.',
    'ambito' => 'notificacion'
  ];

  public const CATEGORIA_MODIFICADA = [
    'titulo' => 'Categoría modificada',
    'mensaje' => 'La categoría ha sido modificada con éxito.',
    'ambito' => 'notificacion'
  ];

  public const CATEGORIA_ELIMINADA = [
    'titulo' => 'Categoría eliminada',
    'mensaje' => 'La categoría ha sido eliminada con éxito.',
    'ambito' => 'notificacion'
  ];

  public const CATEGORIA_EXISTENTE = [
    'titulo' => 'Categoría ya existente',
    'mensaje' => 'La categoría ingresada ya existe.',
    'ambito' => 'notificacion'
  ];

  public const CATEGORIA_NOMBRE_CORTO = [
    'titulo' => 'Nombre muy corto',
    'mensaje' => 'El nombre de la categoría debe tener como mínimo ' . self::NOMBRE_MIN_LONGITUD . ' caracteres.',
    'ambito' => 'general'
  ];

  public const CATEGORIA_NOMBRE_LARGO = [
    'titulo' => 'Nombre muy largo',
    'mensaje' => 'El nombre de la categoría debe tener como máximo ' . self::NOMBRE_MAX_LONGITUD . ' caracteres.',
    'ambito' => 'general'
  ];

  public const NOMBRE_MIN_LONGITUD = 4;
  public const NOMBRE_MAX_LONGITUD = 30;

  private array $errores = [];
  private ?int $idCategoria;
  private ?string $nombreCategoria;

  public function __construct(?string $nombreCategoria = null, ?int $idCategoria = null)
  {
    $this->idCategoria = $idCategoria;
    $this->nombreCategoria = $nombreCategoria;
  }

  public function registrarCategoria(): void
  {
    $this->nombreCategoria = trim($this->nombreCategoria);
    $this->nombreCategoria = preg_replace('/\s+/', ' ', $this->nombreCategoria);

    if ($this->camposVacios()) {
      array_push($this->errores, Respuesta::CAMPO_VACIO);
    } else {
      $this->validarNombreCategoria();
      $this->buscarCategoriaExistente();
      $this->validarLongitud();
    }

    if (count($this->errores) > 0) {
      $respuesta = new Respuesta(Respuesta::STATUS_ERROR, Respuesta::ARRAY, $this->errores);
      exit($respuesta->Json());
    }

    $idCategoria = $this->crearCategoria($this->nombreCategoria);

    echo (new Respuesta(Respuesta::STATUS_EXITO, Respuesta::ARRAY, array($this->nombreCategoria, $idCategoria, self::CATEGORIA_REGISTRADA)))->Json();
  }

  public function modificarCategoria(): void
  {
    if ($this->camposVacios()) {
      array_push($this->errores, Respuesta::CAMPO_VACIO);
    } else {
      $this->validarNombreCategoria();
      $this->buscarCategoriaExistente();
      $this->validarLongitud();
    }

    if (count($this->errores) > 0) {
      $respuesta = new Respuesta(Respuesta::STATUS_ERROR, Respuesta::ARRAY, $this->errores);
      exit($respuesta->Json());
    }

    $this->actualizarCategoria($this->idCategoria, $this->nombreCategoria);

    echo (new Respuesta(Respuesta::STATUS_EXITO, Respuesta::ARRAY, array(self::CATEGORIA_MODIFICADA)))->Json();
  }

  public function removerCategoria()
  {
    $this->eliminarCategoria($this->idCategoria);

    echo (new Respuesta(Respuesta::STATUS_EXITO, Respuesta::ARRAY, array(self::CATEGORIA_ELIMINADA)))->Json();
  }

  private function camposVacios(): bool
  {
    return (empty($this->nombreCategoria));
  }

  private function validarNombreCategoria(): void
  {
    $caracteresEspeciales = array('á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', 'Ü', ' ');
    $caracteresNormalizados = array('a', 'e', 'i', 'o', 'u', 'n', 'u', 'A', 'E', 'I', 'O', 'U', 'N', 'U', '');
    $nombreCategoria = str_replace($caracteresEspeciales, $caracteresNormalizados, $this->nombreCategoria);

    if (!ctype_alpha($nombreCategoria)) {
      array_push($this->errores, self::CATEGORIA_INVALIDA);
    }
  }

  private function buscarCategoriaExistente(): void
  {
    if ($this->categoriaExistente($this->nombreCategoria)) {
      array_push($this->errores, self::CATEGORIA_EXISTENTE);
    }
  }

  private function validarLongitud(): void
  {
    if (strlen($this->nombreCategoria) < self::NOMBRE_MIN_LONGITUD) {
      array_push($this->errores, self::CATEGORIA_NOMBRE_CORTO);
    } else if (strlen($this->nombreCategoria) > self::NOMBRE_MAX_LONGITUD) {
      array_push($this->errores, self::CATEGORIA_NOMBRE_LARGO);
    }
  }
}
