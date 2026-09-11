import { useEffect, useState } from 'react';
import { getProduct } from '../api/products';
import { addToCart } from '../api/cart';
import type { Product } from '../types/product';
import { useParams, useNavigate } from 'react-router-dom';

function ProductDetailPage() {
  const { id } = useParams<{ id: string }>();
  const [product, setProduct] = useState<Product | null>(null);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();


  useEffect(() => {
    if (!id) return;
    getProduct(id)
      .then((data) => setProduct(data))
      .finally(() => setLoading(false));
  }, [id]);

  async function handleAddToCart() {
  if (!product) return;

  const token = localStorage.getItem('token');
  if (!token) {
    navigate('/login');
    return;
  }

  try {
    await addToCart(product.id);
    alert('Ürün sepete eklendi!');
  } catch (err) {
    alert('Ürün sepete eklenemedi.');
  }

  }

  if (loading) return <p>Yükleniyor...</p>;
  if (!product) return <p>Ürün bulunamadı.</p>;

  return (
    <div className="product-detail">
      <h2>{product.name}</h2>
      <p className="product-detail-price">{product.price} ₺</p>
      <p>{product.description}</p>
      <p>Stok: {product.stock}</p>
      <button onClick={handleAddToCart}>Sepete Ekle</button>
    </div>
  );
}

export default ProductDetailPage;