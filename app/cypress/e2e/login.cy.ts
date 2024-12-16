describe('Login e Redirecionamento', () => {
  it('Deve permitir login e redirecionar para a tela principal', () => {
    // Visita a página inicial
    cy.visit('http://localhost:5173/login'); // Altere para o endereço do seu projeto

    // Preenche o email e senha
    cy.get('input#email').type('johndoe@mail.com');
    cy.get('input#password').type('@12345678');

    // Clica no botão de login
    cy.get('button').contains('Entrar').click();

    // Verifica se redirecionou para a tela principal
    cy.url().should('eq', 'http://localhost:5173/');

    // Verifica se há elementos esperados na página principal
    cy.contains('Testar Endpoints').should('be.visible');
    cy.contains('GET').should('be.visible');
    cy.contains('POST').should('be.visible');
  });
});