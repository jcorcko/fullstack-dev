const AuthLayout = ({ children }) => {
  return (
    <div className="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light">
      <div className="card shadow-sm" style={{ width: '100%', maxWidth: '400px' }}>
        <div className="card-body p-4">
          {children}
        </div>
      </div>
    </div>
  );
};

export default AuthLayout;
