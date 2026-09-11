import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { getProducts } from '../api/products';
import { getCategories } from '../api/categories';
import type { Product } from '../types/product';
import type { Category } from '../types/category';

function HomePage() {
  const [products, setProducts] = useState<Product[]>([]);
  const [categories, setCategories] = useState<Category[]>([]);
  const [selectedCategory, setSelectedCategory] = useState<number | null>(null);
  const [loading, setLoading] = useState(true);
  const [currentPage, setCurrentPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);

  useEffect(() => {
    getCategories().then((result) => setCategories(result));
  }, []);

  useEffect(() => {
    setLoading(true);
    getProducts(currentPage, selectedCategory)
      .then((result) => {
        setProducts(result.data);
        setLastPage(result.meta.last_page);
      })
      .finally(() => setLoading(false));
  }, [currentPage, selectedCategory]);

  function handleCategoryClick(categoryId: number | null) {
    setSelectedCategory(categoryId);
    setCurrentPage(1);
  }

  if (loading) {
    return <p>Yükleniyor...</p>;
  }

  return (
    <div>
      <div className="category-filter">
        <button
          onClick={() => handleCategoryClick(null)}
          className={selectedCategory === null ? 'active' : ''}
        >
          Tümü
        </button>
        {categories.map((category) => (
          <button
            key={category.id}
            onClick={() => handleCategoryClick(category.id)}
            className={selectedCategory === category.id ? 'active' : ''}
          >
            {category.name}
          </button>
        ))}
      </div>

      {products.length === 0 ? (
        <p>Bu kategoride henüz ürün yok.</p>
      ) : (
        <div className="product-grid">
          {products.map((product) => (
            <Link key={product.id} to={`/products/${product.id}`} className="product-card">
              <h3>{product.name}</h3>
              <p>{product.price} ₺</p>
            </Link>
          ))}
        </div>
      )}

      <div className="pagination">
        <button onClick={() => setCurrentPage((p) => p - 1)} disabled={currentPage === 1}>
          Önceki Sayfa
        </button>
        <span>Sayfa {currentPage} / {lastPage}</span>
        <button onClick={() => setCurrentPage((p) => p + 1)} disabled={currentPage === lastPage}>
          Sonraki Sayfa
        </button>
      </div>
    </div>
  );
}

export default HomePage;