<?php
// Inclui nosso arquivo de conexão da pasta config
require_once 'config/conexao.php';

$mensagem = '';
$estados_brasileiros = [ 'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
    'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
    'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul',
    'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
    'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
    'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina',
    'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $sexo = $_POST['sexo'] ?? '';
    $data_nasc = $_POST['data_nasc'] ?? '';
    $senha_digitada = $_POST['senha'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $telefone = $_POST['telefone'] ?? '';


    // Validação simples para campos obrigatórios do nosso BD
    if (empty($nome) || empty($email) || empty($data_nasc) || empty($senha_digitada) || empty($cidade) ) {
        $mensagem = "❌ Erro: Todos os campos marcados com * são obrigatórios!";
    } else {
        // PREPARAR INSERT ATUALIZADO
        $stmt = $connect->prepare("
            INSERT INTO usuarios (nome, email, sexo, data_nasc, senha, cidade, estado, telefone)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $senhaHash = password_hash($senha_digitada, PASSWORD_DEFAULT);
        
        
        $stmt->bind_param("ssssssss", $nome, $email, $sexo, $data_nasc, $senhaHash, $cidade, $estado, $telefone);

        if ($stmt->execute()) {
            $mensagem = "✅ Usuário cadastrado com sucesso! Você será redirecionado para a página de login.";
            header("refresh:3;url=index.php");
        } else {
            $mensagem = "❌ Erro ao cadastrar: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
        <div class="split-container">


    <div class="metade-imagemC">
    </div>


    <div class="metade-formulario">
    
    <div class="cadastro-container">
    
    <h2>Crie sua Conta</h2>
        
        <?php if (!empty($mensagem)): ?>
            <p class="error"><strong><?php echo $mensagem; ?></strong></p>
        <?php endif; ?>

        <form action="cadastro.php" method="post"> <div class="form-group">
                <label>Nome Completo </label>
                <input type="text" name="nome" required>
            </div>
            <div class="form-group">
                <label>Email </label>
                <input type="email" name="email" required>
            </div>
             <div class="form-group">
                <label>Senha </label>
                <input type="password" name="senha" required>
            </div>
            <div class="form-group">
                <label>Data de Nascimento </label>
                <input type="date" name="data_nasc" required>
            </div>
            <div class="form-group">
                <label>Sexo</label>
                <input type="radio" value="M" name="sexo"> Masculino
                <input type="radio" value="F" name="sexo"> Feminino
            </div>
            <div class="form-group">
                <label>Telefone</label>
                <input type="text" name="telefone">
        </div>
        <br>
           <div class="form-group">
            <label>Cidade </label>
            <input type="text" name="cidade" id="cidade" list="lista-cidades" required autocomplete="off">
            <datalist id="lista-cidades">
                </datalist>
                <br>
        <div class="form-group">
            <label>Estado </label>
            <select name="estado" id="estado" required>
                <option value="">-- Selecione --</option>
                <?php
                // Seu código que gera os estados continua igual
                foreach ($estados_brasileiros as $sigla => $nome_estado) {
                    echo "<option value=\"$sigla\">$nome_estado</option>";
                }
            ?>
    </select>
</div>
            <br>
            <button type="submit" class="btn">Concluir Cadastro</button>
            <p style="text-align: center; margin-top: 15px;">
                Já tem uma conta? <a href="index.php">Faça o login</a>
            </p>
        </form>

                </div>
        </div>
        </div>
    </div>
           </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        // 1. PEGAR OS ELEMENTOS DO FORMULÁRIO
        const cidadeInput = document.getElementById('cidade');
        const estadoSelect = document.getElementById('estado');
        const cidadesDatalist = document.getElementById('lista-cidades');

        let municipios = [];

    
        fetch('https://servicodados.ibge.gov.br/api/v1/localidades/municipios?orderBy=nome')
            .then(response => response.json()) // Converte a resposta para o formato JSON
            .then(data => {
                municipios = data; // Guarda a lista de cidades na nossa variável

                // Preenche o datalist com as opções de cidade para o autocomplete
                municipios.forEach(municipio => {
                    const option = document.createElement('option');
                    option.value = municipio.nome;
                    cidadesDatalist.appendChild(option);
                });
            })
            .catch(error => console.error('Erro ao buscar municípios:', error));


        cidadeInput.addEventListener('input', function() {
            const nomeCidadeDigitada = this.value;

           
            const cidadeEncontrada = municipios.find(mun => mun.nome.toLowerCase() === nomeCidadeDigitada.toLowerCase());

            // 4. SE ENCONTRAR A CIDADE, ATUALIZA O ESTADO
            if (cidadeEncontrada) {
                // Pega a sigla do estado (ex: "MG") de dentro do objeto da cidade
                const siglaEstado = cidadeEncontrada.microrregiao.mesorregiao.UF.sigla;
                
                estadoSelect.value = siglaEstado;
            }
        });
    });
</script>
</body>

</html>