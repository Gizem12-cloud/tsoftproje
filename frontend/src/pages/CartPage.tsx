import { useEffect, useState } from 'react';
import { getCart, addToCart, decreaseFromCart, removeFromCart } from '../api/cart';
import type { Cart } from '../types/cart';
import { useNavigate } from 'react-router-dom';
import { createOrder } from '../api/orders';

function CartPage() {
  const [cart, setCart] = useState<Cart | null>(null);
  const [loading, setLoading] = useState(true);


  const navigate = useNavigate();
const [submitting, setSubmitting] = useState(false);

async function handleCheckout() {
  setSubmitting(true);
  try {
    const order = await createOrder();
    alert(`Siparişiniz oluşturuldu! Sipariş No: ${order.id}`);
    navigate('/');
  } catch (err) {
    alert('Sipariş oluşturulamadı. Lütfen tekrar deneyin.');
  } finally {
    setSubmitting(false);
  }
}
  useEffect(() => {
    getCart()
      .then((result) => setCart(result))
      .finally(() => setLoading(false));
  }, []);

  async function handleIncrease(productId: number) {
    const updatedCart = await addToCart(productId, 1);
    setCart(updatedCart);
  }

  async function handleDecrease(productId: number) {
    const updatedCart = await decreaseFromCart(productId);
    setCart(updatedCart);
  }

  async function handleRemove(productId: number) {
    const updatedCart = await removeFromCart(productId);
    setCart(updatedCart);
  }

  if (loading) {
    return <p>Yükleniyor...</p>;
  }

  if (!cart || cart.items.length === 0) {
    return <p>Sepetiniz boş.</p>;
  }

  const total = cart.items.reduce(
    (sum, item) => sum + parseFloat(item.product.price) * item.quantity,
    0
  );

  return (
    <div className="cart-page">
      {cart.items.map((item) => (
        <div key={item.id} className="cart-item">
          <div>
            <h3>{item.product.name}</h3>
            <p>{item.product.price} ₺</p>
          </div>
          <div className="cart-item-controls">
            <button onClick={() => handleDecrease(item.product_id)}>-</button>
            <span>{item.quantity}</span>
            <button onClick={() => handleIncrease(item.product_id)}>+</button>
          </div>
          <p>{(parseFloat(item.product.price) * item.quantity).toFixed(2)} ₺</p>
          <button onClick={() => handleRemove(item.product_id)}>Kaldır</button>
        </div>
      ))}

      <div className="cart-total">
      <button onClick={handleCheckout} disabled={submitting}>
  {submitting ? 'İşleniyor...' : 'Sepeti Onayla'}
</button>
        <strong>Toplam: {total.toFixed(2)} ₺</strong>
      </div>
    </div>
  );
}

export default CartPage;