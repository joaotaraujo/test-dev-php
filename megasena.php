<?php

class megasena
{

    private $numDezenas;
    private $resultado;
    private $totalJogos;
    private $jogos;

    //construtor da classe com restricao para o atributo $numDezenas
    public function __construct($numDezenas, $totalJogos)
    {
        try
        {
            if($numDezenas < 6 or $numDezenas > 10)
            {
                throw new Exception("Numero de dezenas invalido (deve estar entre 6 e 10!)");
            }
            else
            {
                $this->numDezenas = $numDezenas;
                $this->totalJogos = $totalJogos;
            }
        }
        catch (Exception $e)
        {
            echo 'Erro ao instanciar a classe megasena: ',  $e->getMessage();
        }
    }


    //getters
    public function getNumDezenas()
    {
        return $this->numDezenas;
    }

    public function getResultado()
    {
        return $this->resultado;
    }

    public function getTotalJogos()
    {
        return $this->totalJogos;
    }

    public function getJogos()
    {
        return $this->jogos;
    }

    //setters
    public function setNumDezenas($numDezenas)
    {
        $this->numDezenas = $numDezenas;
    }

    public function setResultado($resultado)
    {
        $this->resultado = $resultado;
    }

    public function setTotalJogos($totalJogos)
    {
        $this->totalJogos = $totalJogos;
    }

    public function setJogos($jogos)
    {
        $this->jogos = $jogos;
    }

    //funcao para criar as dezenas (gerar um jogo)
    //por ser privada, so podera ser acessada pela classe megasena;
    //consequentemente, a cardinalidade já terá sido tratada no construtor.
    private function gerarDezenas($numDezenas)
    {
        $arrayDezenas = array();

        while($numDezenas != 0)
        {
            $dezenaAleatoria = rand(1, 60);
            if(!in_array($dezenaAleatoria, $arrayDezenas))
            {
                $arrayDezenas[] = $dezenaAleatoria;
                $numDezenas--;
            }
        }

        sort($arrayDezenas);

        return $arrayDezenas;
    }

    //funcao para gerar todos os jogos (conjunto de dezenas) da megasena
    public function gerarJogos()
    {
        $this->jogos = array();

        for($i = 1; $i <= $this->totalJogos; $i++)
        {
            $this->jogos[$i] = $this->gerarDezenas($this->numDezenas);
        }
    }

    //funcao para gerar o resultado
    //a cardinalidade é estática (6)
    public function sortearMegasena()
    {
        $this->resultado = $this->gerarDezenas(6);
    }

    //funcao para conferir todas as dezenas com os jogos
    public function conferirJogos()
    {

        $tabelaHTML = '<table border="1">'.
                        '<tr>'.
                        '    <td>Jogo</td>'.
                        '    <td>Dezenas</td>'.
                        '    <td>Pontuacao</td>'.
                        '</tr>';


        for($i = 1; $i <= $this->totalJogos; $i++)
        {

            $tabelaHTML .= '<tr>'.
                            '<td>Jogo ' . $i . '&nbsp;&nbsp;</td>'.
                            '<td>&nbsp;&nbsp;';

            $pontuacaoAtual = 0;

            foreach($this->jogos[$i] as $numJogo)
            {
                if(in_array($numJogo,$this->resultado))
                {
                    $tabelaHTML .= '<b style="color:yellowgreen;">' . $numJogo . '</b>&nbsp;&nbsp;';
                    $pontuacaoAtual++;
                }
                else
                {
                    $tabelaHTML .= $numJogo . '&nbsp;&nbsp;';
                }
            }

            $tabelaHTML .=  '</td>'.
                            '<td><center>' . $pontuacaoAtual . '</center></td>'.
                        '</tr>';

        }

        $tabelaHTML .= "</table><br><b>Megasena sorteada:</b>&nbsp;&nbsp;";
        foreach($this->resultado as $numSorteado)
        {
            $tabelaHTML .= "$numSorteado&nbsp;&nbsp;";
        }

        return $tabelaHTML;

    }

}

echo '<h2>Jogo da megasena</h2>'.
     '<form action="megasena.php" method="post">'.
        '<p>Número de dezenas: <input type="text" name="numDezenas" /></p>'.
        '<p>Número de jogos: <input type="text" name="numJogos" /></p>'.
        '<p><input type="submit" value="Jogar!"/></p>'.
    '</form>';


//Caso haja solicitação de jogo...
if (!isset($_POST['submit']) and
    !is_null($_POST['numDezenas']) and
    !is_null($_POST['numJogos']))
{
    $megasena = new megasena($_POST['numDezenas'], $_POST['numJogos']);

    //caso o número de dezenas esteja na cardinalidade correta...
    if ($megasena->getNumDezenas() != null)
    {
        $megasena->gerarJogos();
        $megasena->sortearMegasena();
        echo $megasena->conferirJogos();
    }
}
