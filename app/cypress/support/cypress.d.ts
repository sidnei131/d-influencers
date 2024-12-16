/// <reference types="cypress" />

declare global {
  namespace Cypress {
    interface Chainable {
      /**
       * Comando customizado para fazer login
       * @param email - Email do usuário
       * @param password - Senha do usuário
       */
      login(email: string, password: string): Chainable<void>;
    }
  }
}

export {};
