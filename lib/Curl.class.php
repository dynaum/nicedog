<?php
/**
 *
 * File.inc.php
 *   classe para gerenciamento de conexoes com cURL
 *
 * @author Elber Ribeiro
 * @version 0.1
 * @created 03-Abr-2009 11:54:00
 */

class Curl
{
    private $url;
    private $fields;
    private $request;
    
    public function __construct( $url, $request )
    {
        $this->url = $url;
        $this->request = $request;
    }
    
    /**
	 * Seta as variaveis, executa a conexao e retorna o resultado
	 *
	 *
	 */
    public function execute()
    {
        $ch = curl_init();
        
        // Definindo o caminho do servidor
        curl_setopt($ch, CURLOPT_URL, $this->url);
    
        // Colocando a opcao de retorno para variavel
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Definindo o tipo de request
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->request);

        // Adiocionando campos se necessario
        if( !empty( $this->post ) )
        {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->post);
        }

        // executando e pegando o resultado
        $output = curl_exec($ch);

        // fecha conexao
        curl_close($ch);
        
        return $output;
    }
    
    /**
	 * Adiciona campos no metodo POST
	 *   para arquivos adicionar @ no value com o caminho completo
	 *
	 *
	 * @return string
	 *
	 */
    public function addPostFields( $fields )
    {
        $this->fields[] = $fields;
    }
}