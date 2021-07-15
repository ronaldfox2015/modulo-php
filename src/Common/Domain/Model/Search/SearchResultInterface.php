<?php

namespace Bumeran\Common\Domain\Model\Search;

/**
 * Interface SearchResultInterface
 *
 * @package Bumeran\Common\Domain\Model\Search
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
interface SearchResultInterface
{
    /**
     * @return int
     */
    public function getTotal();

    /**
     * @return int
     */
    public function getPages();

    /**
     * @return SearchIterator
     */
    public function getItems();

    /**
     * @return array
     */
    public function getFacets();

    /**
     * @param array $options Opciones de los resultados de busqueda
     *    $options = [
     *      'siteUrl'    =>  Url del sitio (url del ambiente dev/pre/prod),
     *      'logosUrl'   =>  Cdn de los logos de las empresas
     *      'staticsUrl' =>  Cdn de los staticos
     *      'favorites'  =>  Lista de empleos favoritos de un postulante
     *      'applied'    =>  Lista de empleos a los que el postulante ha postulado
     *    ];
     *
     * @return void
     */
    public function setOptions(array $options = []);

    /**
     * Setear el iterador de los resultados.
     * Esto es util para adecuar la data en distintos escenarios.
     *
     * @param SearchIterator $iterator
     * @return mixed
     */
    public function setIterator(SearchIterator $iterator);
}
