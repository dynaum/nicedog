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
    private $ch;
    private $url;
    private $fields;
    private $request;
    private $info;
    private $get_info;
    
    public function __construct( $url, $request, $get_info = false )
    {
        $this->url = $url;
        $this->request = $request;
        $this->get_info = $get_info;
    }
    
    /**
	 * Seta as variaveis, executa a conexao e retorna o resultado
	 *	 * @return string
	 *
	 */
    public function execute()
    {
        $this->ch = curl_init();
        
        // Definindo o caminho do servidor
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
    
        // Colocando a opcao de retorno para variavel
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        
        // Definindo o tipo de request
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $this->request);

        // Adiocionando campos se necessario
        if( $this->fields )
        {
            curl_setopt( $this->ch, CURLOPT_POSTFIELDS, $this->fields );
        }

        // executando e pegando o resultado
        $output = curl_exec($this->ch);
        
        // pega as informacoes da conexao
        if( $this->get_info )
        {
            $this->setInfo();
        }
        
        // fecha conexao
        curl_close($this->ch);
        
        return $output;
    }
    
    /**
	 * Adiciona campos no metodo POST
	 *   para arquivos adicionar @ no value com o caminho completo
	 *	 * @param array
	 *
	 * @return string
	 *
	 */
    public function addFields( $fields )
    {
        $this->fields = empty( $this->fields ) ? $fields : array_merge( $this->fields, $fields );
    }
    
    /**
	 * Extrai informacoes da conexao
	 *s	 */
    private function setInfo()
    {
        //Tempo total da transação em segundos para a última transferencia
        $this->info['tempo_total'] = curl_getinfo($this->ch, CURLINFO_TOTAL_TIME);
        
        //Tempo em segundos que levou para estabelecer a conexão
        $this->info['tempo_conexao'] = curl_getinfo($this->ch, CURLINFO_CONNECT_TIME);
        
        //Número total de bytes enviados
        $this->info['bytes_enviados'] = curl_getinfo($this->ch, CURLINFO_SIZE_UPLOAD);
        
        //Número total de bytes baixados
        $this->info['bytes_baixados'] = curl_getinfo($this->ch, CURLINFO_SIZE_DOWNLOAD);
        
        //Média de tempo do download
        $this->info['media_tempo_download'] = curl_getinfo($this->ch, CURLINFO_SPEED_DOWNLOAD);
        
        //Média de tempo do upload
        $this->info['media_tempo_upload'] = curl_getinfo($this->ch, CURLINFO_SPEED_UPLOAD);
        
        //Tamanho total dos cabeçalhos recebidos
        $this->info['tamanho_cabecalho'] = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
        
        //Tamanho da requisição emitida, atualmente somente para requisições HTTP 
        $this->info['tamanho_requisicao'] = curl_getinfo($this->ch, CURLINFO_REQUEST_SIZE);
    }
    
    /**
	 * Retorna as informacoes de conexao
	 *	 * @return array
	 *
	 */
    public function getInfo()
    {
        return $this->info;
    }
}
