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
	 * @return array
	 *
	 */
    public function toArray()
    {
        return file( $this->file, FILE_IGNORE_NEW_LINES );
    }
}

