// store Pinia pour gérer le panier
import { defineStore } from 'pinia'
import * as cartApi from '../js/api/cartApi'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [],
    totalQuantity: 0,
    totalPrice: 0,
    isEmpty: true,
    loading: false
  }),

  getters: {
    totalHT: (state) => {
      return state.items.reduce((total, item) => {
        const priceHT = item.product.price / (1 + item.product.tvaRate / 100)
        return total + (priceHT * item.quantity)
      }, 0)
    },

    totalTVA: (state) => {
      return state.items.reduce((total, item) => {
        const priceHT = item.product.price / (1 + item.product.tvaRate / 100)
        const tva = priceHT * (item.product.tvaRate / 100)
        return total + (tva * item.quantity)
      }, 0)
    },

    totalTTC: (state) => {
      return state.items.reduce((total, item) => {
        return total + (item.product.price * item.quantity)
      }, 0)
    }
  },

  actions: {
    async fetchSummary() {
      this.loading = true
      try {
        const data = await cartApi.getSummary()
        this.items = data.items
        this.totalQuantity = data.totalQuantity
        this.totalPrice = data.totalTTC
        this.isEmpty = this.totalQuantity === 0
      } catch (error) {
        console.error('Erreur lors de la récupération du panier:', error)
        this.totalQuantity = 0
        this.isEmpty = true
      }
      this.loading = false
    },

    async addToCart(productId, quantity = 1) {
      this.loading = true
      const response = await cartApi.addToCart(productId, quantity)
      await this.fetchSummary()
      this.loading = false
      return response.message || 'Produit ajouté au panier'
    },

    async updateQuantity(productId, quantity) {
      this.loading = true
      // Simuler la mise à jour via suppression puis ajout
      await cartApi.removeFromCart(productId)
      if (quantity > 0) {
        await cartApi.addToCart(productId, quantity)
      }
      await this.fetchSummary()
      this.loading = false
    },

    async removeProduct(productId) {
      this.loading = true
      await cartApi.removeFromCart(productId)
      await this.fetchSummary()
      this.loading = false
    },

    async clearCart() {
      this.loading = true
      await cartApi.clearCart()
      await this.fetchSummary()
      this.loading = false
    },

    async fetchSummaryForCartPage() {
      this.loading = true
      try {
        const data = await cartApi.getSummary()
        this.items = data.items
        this.totalQuantity = data.totalQuantity
        this.totalPrice = data.totalTTC
        this.isEmpty = this.totalQuantity === 0
      } catch (error) {
        console.error('Erreur lors de la récupération du panier:', error)
        this.totalQuantity = 0
        this.isEmpty = true
      }
      this.loading = false
    },

    async proceedToCheckout() {
      window.location.href = '/panier/checkout'
    }
  },
})
