import { useNavigate } from "react-router-dom";

export default function LogoutButton() {
  const navigate = useNavigate();

  const logout = () => {
    localStorage.removeItem("user");
    localStorage.removeItem("isAuthenticated");
    navigate("/login");
  };

  return (
    <button
      onClick={logout}
      className="w-full bg-red-600 py-2 rounded hover:bg-red-700 transition"
    >
      Logout
    </button>
  );
}
