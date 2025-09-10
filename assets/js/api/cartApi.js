function getDefaultHeaders() {
  return {
    'X-Requested-With': 'XMLHttpRequest',
    'Content-Type': 'application/x-www-form-urlencoded',
  }
}

function getJsonHeaders() {
  return {
    'X-Requested-With': 'XMLHttpRequest',
  }
}

async function parseJsonResponse(res) {
  const text = await res.text()

  try {
    const data = JSON.parse(text)

    if (!res.ok || data.error) {
      throw new Error(data.error || `Erreur HTTP ${res.status}`)
    }

    return data
  } catch (err) {
    console.warn(' Réponse non JSON valide :', text)
    throw new Error('Erreur inattendue : réponse non JSON')
  }
}

// 🔼 AJOUTER AU PANIER
export async function addToCart(productId, quantity = 1) {
  const res = await fetch(`/panier/add/${productId}`, {
    method: 'POST',
    headers: getDefaultHeaders(),
    body: new URLSearchParams({ quantity }),
  })

  return await parseJsonResponse(res)
}

// 🔽 RETIRER DU PANIER
export async function removeFromCart(productId) {
  const res = await fetch(`/panier/remove/${productId}`, {
    method: 'POST',
    headers: getJsonHeaders(),
  })

  return await parseJsonResponse(res)
}

// VIDER LE PANIER
export async function clearCart() {
  const res = await fetch(`/panier/clear`, {
    method: 'POST',
    headers: getJsonHeaders(),
  })

  return await parseJsonResponse(res)
}
//  RÉSUMÉ DU PANIER
export async function getSummary() {
  const res = await fetch(`/panier/summary`, {
    method: 'GET',
    headers: getJsonHeaders(),
  })

  return await parseJsonResponse(res)
}
