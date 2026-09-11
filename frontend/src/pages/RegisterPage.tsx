import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { register } from '../api/auth';

function RegisterPage() {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [error, setError] = useState('');
  const navigate = useNavigate();

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    setError('');

    try {
      const data = await register(name, email, password, passwordConfirmation);
      localStorage.setItem('token', data.token);
      navigate('/');
    } catch (err) {
      setError('Kayıt olurken bir hata oluştu. Bilgilerini kontrol et.');
    }
  }

  return (
    <form onSubmit={handleSubmit} className="auth-form">
      <h2>Kayıt Ol</h2>
      {error && <p className="error-text">{error}</p>}
      <input
        type="text"
        placeholder="Ad Soyad"
        value={name}
        onChange={(e) => setName(e.target.value)}
      />
      <input
        type="email"
        placeholder="Email"
        value={email}
        onChange={(e) => setEmail(e.target.value)}
      />
      <input
        type="password"
        placeholder="Şifre"
        value={password}
        onChange={(e) => setPassword(e.target.value)}
      />
      <input
        type="password"
        placeholder="Şifre (Tekrar)"
        value={passwordConfirmation}
        onChange={(e) => setPasswordConfirmation(e.target.value)}
      />
      <button type="submit">Kayıt Ol</button>
    </form>
  );
}

export default RegisterPage;