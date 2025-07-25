// store Pinia pour gérer le panier
import { defineStore } from 'pinia'
import * as cartApi from '../js/api/cartApi'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [],
    totalQuantity: 0,
    totalPrice: 0,
    isEmpty: true,
    loading: false,
  }),

  actions: {
    async fetchSummary() {
      this.loading = true
      const data = await cartApi.getSummary()
      this.items = data.items
      this.totalQuantity = data.totalQuantity
      this.totalPrice = data.totalTTC
      this.isEmpty = this.totalQuantity === 0
      this.loading = false
    },

   async addToCart(productId, quantity = 1) {
  this.loading = true
  const response = await cartApi.addToCart(productId, quantity)
  await this.fetchSummary()
  this.loading = false
  return response.message || 'Produit ajouté au panier'
},


    async removeProduct(productId) {
      this.loading = true
      await cartApi.removeFromCart(productId)
      await this.fetchSummary()
    },

    async clearCart() {
      this.loading = true
      await cartApi.clearCart()
      await this.fetchSummary()
    },
  },
})
