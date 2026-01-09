import { Routes, Route } from 'react-router-dom';
import Login from '../pages/Login';
import Dashboard from '../pages/Dashboard';
import NotFound from '../pages/NotFound';
import RequireAuth from '../auth/RequireAuth';
import AuthLayout from '../layouts/AuthLayout';
import AppLayout from '../layouts/AppLayout';

const AppRoutes = () => (
  <Routes>
    <Route
      path="/login"
      element={
        <AuthLayout>
          <Login />
        </AuthLayout>
      }
    />

    <Route
      path="/"
      element={
        <RequireAuth>
          <AppLayout>
            <Dashboard />
          </AppLayout>
        </RequireAuth>
      }
    />

    <Route path="*" element={<NotFound />} />
  </Routes>
);

export default AppRoutes;
