import { useAuth } from '../auth/useAuth';

const AppLayout = ({ children }) => {
  const { user, logout } = useAuth();

  return (
    <>
      <nav className="navbar navbar-dark bg-dark">
        <div className="container-fluid">
          <span className="navbar-brand">Mi App</span>
          <div className="d-flex align-items-center gap-3 text-white">
            <span>{user?.name}</span>
            <button className="btn btn-outline-light btn-sm" onClick={logout}>
              Salir
            </button>
          </div>
        </div>
      </nav>

      <main className="container py-4">
        {children}
      </main>
    </>
  );
};

export default AppLayout;
