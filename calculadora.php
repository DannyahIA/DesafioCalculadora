<?php
// OBS: Bom dia professor, Daniel Tavares aqui, fiz o código completamente sozinho, 
// não faço a menor ideia de quem seja o rapaz que entrou no grupo no blackboard, 
// ele não entrou em contato comigo e muito menos ajudou com o código.
// 
// Feito por Daniel Tavares da Mata, RGM: 34595252

// Inicia a sessão
session_start();

// Inicializa o estado dos campos
if (!isset($numero1) && !isset($numero2) && !isset($resultado) && !isset($operacao)) {
  $resultado = '';
  $numero1 = '';
  $numero2 = '';
  $operacao = 'soma';
}

// Inicializa o estado do botão M
if (!isset($_SESSION['botao_m_state'])) {
  $_SESSION['botao_m_state'] = 'salvar';
}

// Inicializa o estado dos valores
if (!isset($_SESSION['valores'])) {
  $_SESSION['valores'] = "";
}

// Inicializa o histórico se não estiver definido na sessão
if (!isset($_SESSION['historico'])) {
  $_SESSION['historico'] = "";
}

// Função para calcular o fatorial de um número
function factorial($n)
{
  if ($n === 0) {
    return 1;
  }

  $i = $n;
  $calc = 1;
  while ($i > 1) {
    $calc *= $i;
    $i--;
  }
  return $calc;
}

// Salva os valores dos campos na sessão
function salvarValores()
{
  // Verifica se 'numero1', 'numero2', 'operacao' e 'resultado' estão definidos em $_POST antes de acessá-los
  $numero1 = isset($_POST['numero1']) ? $_POST['numero1'] : '';
  $numero2 = isset($_POST['numero2']) ? $_POST['numero2'] : '';
  $operacao = isset($_POST['operacao']) ? $_POST['operacao'] : '';
  $resultado = isset($_POST['resultado']) ? $_POST['resultado'] : '';

  // Salva os valores apenas se estiverem definidos
  $_SESSION['valores'] = array(
    'numero1' => $numero1,
    'numero2' => $numero2,
    'operacao' => $operacao,
    'resultado' => $resultado
  );
}

function carregarValores()
{
  // Verifica se existem valores na sessão
  if (isset($_SESSION['valores'])) {
    // Atribui os valores da sessão às variáveis locais
    $numero1 = $_SESSION['valores']['numero1'];
    $numero2 = $_SESSION['valores']['numero2'];
    $operacao = $_SESSION['valores']['operacao'];
    $resultado = $_SESSION['valores']['resultado'];

    // Retorna os valores carregados
    return array('numero1' => $numero1, 'numero2' => $numero2, 'operacao' => $operacao, 'resultado' => $resultado);
  } else {
    // Retorna um array vazio se não houver valores na sessão
    return array('numero1' => '', 'numero2' => '', 'operacao' => '', 'resultado' => '');
  }
}

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Verifica se o botão M foi pressionado
  if (isset($_POST['botao_m'])) {
    // Alterna o estado do botão M entre 'salvar' e 'carregar'
    $_SESSION['botao_m_state'] = ($_SESSION['botao_m_state'] == 'salvar') ? 'carregar' : 'salvar';
  }

  // Verifica se o botão de limpar histórico foi pressionado
  if (isset($_POST['limpar_historico'])) {
    // Limpa o histórico definindo-o como uma string vazia
    $_SESSION['historico'] = "";
  }

  // Verifica o estado atual do botão M
  if ($_SESSION['botao_m_state'] == 'salvar') {
    // Botão M está no modo salvar
    salvarValores();
  } else {
    // Botão M está no modo carregar
    // Carrega os valores dos campos da sessão utilizando a função carregarValores
    $valoresCarregados = carregarValores();

    // Atribui os valores carregados às variáveis
    $numero1 = $valoresCarregados['numero1'];
    $numero2 = $valoresCarregados['numero2'];
    $operacao = $valoresCarregados['operacao'];
    $resultado = $valoresCarregados['resultado'];
  }

  // Verifica se o botão de salvar valores foi pressionado
  if (isset($_POST['salvar_valores'])) {
    salvarValores();

    // Atribui os valores carregados às variáveis
    $numero1 = '';
    $numero2 = '';
    $operacao = 'soma';
    $resultado = '';
  }

  // Verifica se o botão de carregar valores foi pressionado
  if (isset($_POST['carregar_valores'])) {
    // Carrega os valores dos campos da sessão utilizando a função carregarValores
    $valoresCarregados = carregarValores();

    // Atribui os valores carregados às variáveis
    $numero1 = $valoresCarregados['numero1'];
    $numero2 = $valoresCarregados['numero2'];
    $operacao = $valoresCarregados['operacao'];
    $resultado = $valoresCarregados['resultado'];
  }

  if (isset($_POST['calcular'])) {
    // Obtém os valores dos campos do formulário
    $numero1 = $_POST['numero1'];
    if (empty($_POST['numero2'])) {
      $numero2 = 0;
    } else {
      $numero2 = $_POST['numero2'];
    }
    $operacao = $_POST['operacao'];

    // Verifica se o primeiro número foi fornecido
    if (!empty($numero1)) {
      // Converte o valor do primeiro número para número
      $numero1 = floatval($numero1);

      // Realiza a operação selecionada e atribui o resultado
      switch ($operacao) {
        case 'soma':
          if (!empty($numero2)) {
            $numero2 = floatval($numero2);
            $resultado = $numero1 + $numero2;
            $_SESSION['historico'] .= "$numero1 + $numero2 = $resultado\n";
          }
          break;
        case 'subtracao':
          if (!empty($numero2)) {
            $numero2 = floatval($numero2);
            $resultado = $numero1 - $numero2;
            $_SESSION['historico'] .= "$numero1 - $numero2 = $resultado\n";
          }
          break;
        case 'multiplicacao':
          if (!empty($numero2)) {
            $numero2 = floatval($numero2);
            $resultado = $numero1 * $numero2;
            $_SESSION['historico'] .= "$numero1 * $numero2 = $resultado\n";
          }
          break;
        case 'divisao':
          if (!empty($numero2)) {
            $numero2 = floatval($numero2);
            if ($numero2 != 0) {
              $resultado = $numero1 / $numero2;
              $_SESSION['historico'] .= "$numero1 / $numero2 = $resultado\n";
            } else {
              $resultado = "Erro: Divisão por zero";
            }
          }
          break;
        case 'fatoracao':
          $resultado = factorial($numero1);
          $_SESSION['historico'] .= "$numero1! = $resultado\n";
          break;
        case 'potencia':
          if (!empty($numero2)) {
            $numero2 = intval($numero2);
            $resultado = pow($numero1, $numero2);
            $_SESSION['historico'] .= "$numero1^$numero2 = $resultado\n";
          }
          break;
        default:
          $resultado = "Operação inválida";
          break;
      }
    } else {
      $resultado = "Por favor, preencha todos os campos obrigatórios.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calculadora</title>
  <!-- Adiciona o Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
  <div class="container mt-5">
    <h1 class="text-center mb-4">Calculadora PHP</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-row">
        <div class="col">
          <label for="numero1">Número 1</label>
          <input type="text" class="form-control" id="numero1" name="numero1" placeholder="Digite o primeiro número"
            value="<?php echo $numero1; ?>">
        </div>
        <div class="col">
          <label for="operacao">Operação</label>
          <select class="form-control" id="operacao" name="operacao" required>
            <option value="soma" <?php if ($operacao === 'soma')
              echo 'selected'; ?>>Soma (+)</option>
            <option value="subtracao" <?php if ($operacao === 'subtracao')
              echo 'selected'; ?>>Subtração (-)</option>
            <option value="multiplicacao" <?php if ($operacao === 'multiplicacao')
              echo 'selected'; ?>>Multiplicação (*)
            </option>
            <option value="divisao" <?php if ($operacao === 'divisao')
              echo 'selected'; ?>>Divisão (/)</option>
            <option value="fatoracao" <?php if ($operacao === 'fatoracao')
              echo 'selected'; ?>>Fatoração (!)</option>
            <option value="potencia" <?php if ($operacao === 'potencia')
              echo 'selected'; ?>>Potenciação (^)</option>
          </select>
        </div>
        <div class="col">
          <label for="numero2">Número 2</label>
          <input type="text" class="form-control" id="numero2" name="numero2" placeholder="Digite o segundo número"
            value="<?php echo $numero2; ?>">
        </div>
      </div>
      <div class="form-row mt-3">
        <div class="col">
          <button type="submit" class="btn btn-primary btn-block" name="calcular">Calcular</button>
        </div>
      </div>
      <div class="form-row mt-3">
        <div class="col">
          <label for="resultado">Resultado</label>
          <input type="text" class="form-control" id="resultado" name="resultado" value="<?php echo $resultado; ?>"
            readonly>
        </div>
      </div>
      <div class="form-row mt-3">
        <div class="col">
          <button type="submit" class="btn btn-secondary btn-block" name="salvar_valores">Salvar</button>
        </div>
        <div class="col">
          <button type="submit" class="btn btn-secondary btn-block" name="carregar_valores">Carregar</button>
        </div>
        <div class="col">
          <button type="submit" class="btn btn-secondary btn-block" name="botao_m">M</button>
        </div>
        <div class="col">
          <button type="submit" class="btn btn-secondary btn-block" name="limpar_historico">Limpar Histórico</button>
        </div>
      </div>
      <div class="form-row mt-3">
        <div class="col">
          <label for="historico">Histórico</label>
          <textarea class="form-control" id="historico" name="historico" rows="5"
            readonly><?php echo $_SESSION['historico']; ?></textarea>
        </div>
      </div>
    </form>
  </div>

  <!-- Adiciona o Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
    // Função para habilitar ou desabilitar o campo numero2
    function toggleNumero2() {
      var operacao = document.getElementById("operacao").value;
      var numero2Input = document.getElementById("numero2");

      // Se a operação selecionada for "fatoracao", desabilita o campo numero2
      if (operacao === "fatoracao") {
        numero2Input.disabled = true;
      } else {
        numero2Input.disabled = false;
      }
    }

    // Chama a função ao carregar a página
    toggleNumero2();

    // Define um listener para o evento change do combobox operacao
    document.getElementById("operacao").addEventListener("change", toggleNumero2);
  </script>
</body>

</html>