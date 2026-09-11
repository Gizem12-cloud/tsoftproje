import { useEffect, useState } from 'react';
import { getOrders } from '../api/orders';
import type { Order } from '../types/order';

function OrderHistoryPage() {
  const [orders, setOrders] = useState<Order[]>([]);
  const [loading, setLoading] = useState(true);
  const [currentPage, setCurrentPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);

  useEffect(() => {
    setLoading(true);
    getOrders(currentPage)
      .then((result) => {
        setOrders(result.data);
        setLastPage(result.meta.last_page);
      })
      .finally(() => setLoading(false));
  }, [currentPage]);

  function formatDate(dateString: string) {
    return new Date(dateString).toLocaleDateString('tr-TR', {
      day: 'numeric',
      month: 'long',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    });
  }

  if (loading) return <p>Yükleniyor...</p>;

  if (orders.length === 0) {
    return <p>Henüz hiç siparişiniz yok.</p>;
  }

  return (
    <div className="order-history-page">
      {orders.map((order) => (
        <div key={order.id} className="order-card">
          <div className="order-card-header">
            <strong>Sipariş #{order.id}</strong>
            <span>{formatDate(order.created_at)}</span>
          </div>
          <p>Durum: {order.status}</p>
          <ul>
            {order.items.map((item) => (
              <li key={item.id}>
                {item.product_name} x {item.quantity} — {item.unit_price} ₺
              </li>
            ))}
          </ul>
          <strong>Toplam: {order.total_amount} ₺</strong>
        </div>
      ))}

      <div className="pagination">
        <button onClick={() => setCurrentPage((p) => p - 1)} disabled={currentPage === 1}>Önceki Sayfa</button>
        <span>Sayfa {currentPage} / {lastPage}</span>
        <button onClick={() => setCurrentPage((p) => p + 1)} disabled={currentPage === lastPage}>Sonraki Sayfa</button>
      </div>
    </div>
  );
}

export default OrderHistoryPage;