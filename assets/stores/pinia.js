// Instance Pinia partagée pour toute l'application
import { createPinia } from 'pinia'

// Création d'une seule instance Pinia partagée
export const pinia = createPinia()

console.log('🍍 Instance Pinia partagée créée')
