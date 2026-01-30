import { Route, Routes, useLocation } from 'react-router-dom'
import './App.css'
import Profile from './pages/Profile'
import Register from './pages/Register'
import Dashboard from './pages/Dashboard'
import Login from './pages/Login'
import { useEffect, useState } from 'react'

function App() {

  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const location = useLocation();

  useEffect(() => {
    const auth = localStorage.getItem('isAuthenticated');
    setIsAuthenticated(auth === 'true');
  }, [location]);

  return (
    <>
      <Routes>
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        {isAuthenticated && (
          <>
            <Route path="/profile" element={<Profile />} />
            <Route path="/dashboard" element={<Dashboard />} />
          </>
        )}
      </Routes>
    </>
  )
}

export default App
