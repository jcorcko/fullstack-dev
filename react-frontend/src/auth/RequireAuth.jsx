import { Navigate, useLocation } from 'react-router-dom';
import { useAuth } from './useAuth';

const RequireAuth = ({ children }) => {
  const { user, loading } = useAuth();
  const location = useLocation();

  // Mientras se valida el usuario (fetch /user)
  if (loading) {
    return (
      <div className="d-flex justify-content-center align-items-center vh-100">
        <div className="spinner-border text-primary" role="status">
          <span className="visually-hidden">Cargando...</span>
        </div>
      </div>
    );
  }

  // Si no está autenticado → redirigir a login
  if (!user) {
    return (
      <Navigate
        to="/login"
        replace
        state={{ from: location }}
      />
    );
  }

  // Usuario autenticado → renderizar contenido protegido
  return children;
};

export default RequireAuth;
