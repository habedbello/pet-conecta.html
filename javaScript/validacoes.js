// Função para validar o formato e dígitos verificadores de um CPF
function validarCPF(cpf) {
    // Remove todos os caracteres que não são dígitos
    cpf = cpf.replace(/[^\d]+/g, '');

    // Verifica se o CPF tem 11 dígitos
    if (cpf.length !== 11) {
        return false;
    }

    // Verifica se todos os dígitos são iguais (ex: 111.111.111-11)
    // Esses CPFs são considerados inválidos no Brasil
    if (/^(\d)\1{10}$/.test(cpf)) {
        return false;
    }

    let soma;
    let resto;
    soma = 0;

    // Validação do primeiro dígito verificador
    for (let i = 1; i <= 9; i++) {
        // Multiplica cada dígito pelos pesos (10, 9, ..., 2) e soma
        soma = soma + parseInt(cpf.substring(i - 1, i)) * (11 - i);
    }
    // Calcula o resto da divisão por 11, depois multiplica por 10
    resto = (soma * 10) % 11;

    // Se o resto for 10 ou 11, o dígito verificador é 0
    if ((resto === 10) || (resto === 11)) {
        resto = 0;
    }
    // Compara o dígito verificador calculado com o 10º dígito do CPF
    if (resto !== parseInt(cpf.substring(9, 10))) {
        return false;
    }

    soma = 0;
    // Validação do segundo dígito verificador
    for (let i = 1; i <= 10; i++) {
        // Multiplica cada dígito pelos pesos (11, 10, ..., 2) e soma
        soma = soma + parseInt(cpf.substring(i - 1, i)) * (12 - i);
    }
    // Calcula o resto da divisão por 11, depois multiplica por 10
    resto = (soma * 10) % 11;

    // Se o resto for 10 ou 11, o dígito verificador é 0
    if ((resto === 10) || (resto === 11)) {
        resto = 0;
    }
    // Compara o dígito verificador calculado com o 11º dígito do CPF
    if (resto !== parseInt(cpf.substring(10, 11))) {
        return false;
    }

    // Se todas as validações passarem, o CPF é válido
    return true;
}