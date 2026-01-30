import { Route, Routes } from 'react-router-dom'
import './App.css'
import Profile from './pages/Profile'
import Register from './pages/Register'
import Dashboard from './pages/Dashboard'
import Login from './pages/Login'

function App() {

  return (
    <>
      <Routes>
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route path="/profile" element={<Profile />} />
        <Route path="/dashboard" element={<Dashboard />} />
      </Routes>
    </>
  )
}

export default App
