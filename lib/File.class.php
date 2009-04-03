<?php
/**
 *
 * File.inc.php
 *   classe para gerenciamento de arquivos
 *
 * @author Elber Ribeiro
 * @version 0.1
 * @created 02-Abr-2009 18:20:00
 */

class File
{
    private $name;
    private $ext;
    private $path;
    private $file;
    private $t = array( 'k' => 1, 'm' => 2, 'g' => 3 );
    
    public function __construct( $pName, $pPath )
    {
        $this->name = $pName;
        $this->path = $pPath;
        $this->file = "$pPath/$pName";
        
        if( !file_exists( $this->file ) )
        {
            throw new Exception("Arquivo {$this->file} nao exite!");
        }
    }
    
    /**
	 * copia o conteud do arquivo
	 *
	 * @param string $to
	 *    Caminho do arquivo
	 *
	 * @param boolean $compress
	 *    Define se a copia vai ser comprimida ou nao
	 *
	 * @return int
	 *    Quantidade de bytes que foi escrita no arquivo ou FALSE em caso de falha
	 */
    public function copy( $to, $compress = false )
    {
        if( !is_dir($to) )
		{
			mkdir( $to );
		}
        return file_put_contents( "{$to}/{$this->name}", $this->__toString($compress) );
    }
    
    /**
	 * Retorna o conteudo do arquivo
	 *
	 * @param boolean $compress
	 *    Define se o retorno vai ser comprimido ou nao
	 *
	 * @return string
	 *
	 */
    public function __toString( $compress = false )
    {
        return $compress ? gzdeflate( file_get_contents( $this->file ) ) : file_get_contents( $this->file );
    }
    
    /**
	 * Retorna o arquivo em array sem as quebras de linha
	 *
	 * @param string $er
	 *    Expressao Regular para validacao de linha
	 *
	 * @return array
	 *
	 */
    public function toArray( $er )
    {
        $r = array();
        if( $er )
        {
            foreach ( file( $this->file, FILE_IGNORE_NEW_LINES ) as $line )
            {
                preg_match( $er, $line, $result );
                array_shift( $result ); // remove a linha do retorno
                $r[] = $result;
            }
        }
        else
        {
            $r = file( $this->file, FILE_IGNORE_NEW_LINES );
        }
        
        return $r;
    }
    
    /**
	 * Apaga o arquivo
	 *
	 * @return boolean
	 *
	 */
    public function del()
    {
        return unlink( $this->file );
    }
    
    /**
	 * Retorna o tamanho do arquivo
	 *
	 * @param string $format
	 *    Define o valor de retorno
	 *
	 * @return int
	 *
	 */
    public function size( $format = "k" )
    {
        $f = $this->t[ strtolower( $format ) ] * 1024;
        $f = $f < 1 ? 1 : $f;
        return filesize( $this->file ) / $f;
    }
}
