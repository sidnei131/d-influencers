/// <reference path="./cypress.d.ts" />

Cypress.Commands.add('login', (email: string, password: string) => {
  cy.request('POST', 'http://localhost/api/login', { email, password }).then((response) => {
    localStorage.setItem('authToken', response.body.token);
  });
});