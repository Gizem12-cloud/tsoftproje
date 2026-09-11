import { Link, useNavigate } from 'react-router-dom';

function Navbar() {
  const navigate = useNavigate();
  const token = localStorage.getItem('token');

  function handleLogout() {
    localStorage.removeItem('token');
    navigate('/login');
  }

  return (
    <nav className="navbar">
      <Link to="/" className="navbar-brand">tsoftproje</Link>
      <div className="navbar-links">
        {token ? (
          <>
            <Link to="/orders">Siparişlerim</Link>
            <Link to="/cart">Sepet</Link>
            <button onClick={handleLogout} className="navbar-logout">Çıkış Yap</button>
          </>
        ) : (
          <>
            <Link to="/login">Giriş Yap</Link>
            <Link to="/register">Kayıt Ol</Link>
          </>
        )}
      </div>
    </nav>
  );
}

export default Navbar;