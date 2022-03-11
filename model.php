<?php


class model{

    private $db = null;

    public function model(){

        $this->db = new PDO( 'mysql:dbname=dibi-hml;host=127.0.0.1','root','' );
    }

    public function search($url){

        // user input
        $page 	 = isset( $_GET['page'] ) ? (int) $_GET['page'] : 1;
        $perPage = isset( $_GET['per-page'] ) && $_GET['per-page'] <= 50 ? (int) $_GET['per-page'] : 18;

        // positioning
        $start = ( $page > 1 ) ? ( $page * $perPage ) - $perPage : 0;

        // query
        $articles = $db->prepare( "SELECT SQL_CALC_FOUND_ROWS * FROM anuncios LIMIT {$start}, {$perPage}" );
        $articles->execute();
        $articles = $articles->fetchAll( PDO::FETCH_ASSOC );

        // pages
        $total = $db->query( "SELECT FOUND_ROWS() as total" )->fetch()['total'];
        $pages = ceil( $total / $perPage );

        $retorno = ["paginas" => $pages,"anuncios" => $articles];

        return $retorno;
    }
}