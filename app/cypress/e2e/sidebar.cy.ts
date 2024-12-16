describe('Sidebar Tests', () => {
  beforeEach(() => {
    // @ts-ignore
    cy.login('johndoe@mail.com', '@12345678');
    cy.visit('/');
  });

  it('Deve exibir os endpoints na barra lateral', () => {
    cy.contains('Auth').should('be.visible');
    cy.contains('GET').should('be.visible');
    cy.contains('POST').should('be.visible');
    cy.contains('Campaigns').should('be.visible');
  });

  it('Deve preencher o método e URL ao clicar em um endpoint', () => {
    cy.contains('me').click();

    cy.get('select').should('have.value', 'GET');
    cy.get('input[type="text"]').should('have.value', '/me');
  });
});
