import { useAuth } from '../auth/useAuth';

const Dashboard = () => {
  const { user } = useAuth();

  return (
    <div className="row">
      <div className="col-md-6">
        <div className="card shadow-sm">
          <div className="card-body">
            <h5 className="card-title">Dashboard</h5>
            <p className="card-text">
              Bienvenido <strong>{user?.name}</strong>
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
