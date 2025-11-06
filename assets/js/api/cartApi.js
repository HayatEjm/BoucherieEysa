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
  try {
    // Utiliser directement .json() qui gère mieux l'UTF-8
    const data = await res.json()

    if (!res.ok || data.error) {
      throw new Error(data.error || `Erreur HTTP ${res.status}`)
    }

    return data
  } catch (err) {
    // Si erreur de parsing JSON
    if (err instanceof SyntaxError) {
      const text = await res.text()
      console.warn('Réponse non JSON valide :', text)
      throw new Error('Erreur inattendue : réponse non JSON')
    }
    // Si c'est une erreur métier, la propager
    throw err
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
