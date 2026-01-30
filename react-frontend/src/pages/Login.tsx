import { useState } from "react";
import { login } from "../services/authService";
import { useNavigate } from "react-router-dom";

export default function Login() {

    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [errors, setErrors] = useState([]);
    const navigate = useNavigate();

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        setErrors([]);
        login({ email: email, password: password }).then(res => {
            if (res.data.errors) {
                setErrors(res.data.errors);
            } else {
                localStorage.setItem("user", JSON.stringify(res.data));
                localStorage.setItem("isAuthenticated", true.toString());
                navigate("/dashboard");
            }
        });
    }

  return (
    <>
      <div className="flex flex-col items-center justify-center h-screen bg-gray-100 space-y-4">
      <h2 className="text-2xl font-bld text-gray-800">Login</h2>
      {errors.length > 0 && (
        <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative w-80">
            <strong className="font-bold">Errors:</strong>
          <ul className="list-disc list-inside">
            {errors.map((error, index) => (
              <li key={index}>{error}</li>
            ))}
          </ul>
        </div>
      )}

      <form onSubmit={submit} className="bg-white p-6 rounded shadow-md w-80 space-y-4">
        <div>
            <label className="block text-sm font-medium text-gray-700">Email</label>
            <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                name="email"
                className="mt-1 block w-full px-3 py2 border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none"
                placeholder="Enter your email"
            />
        </div>

        <div>
            <label className="block text-sm font-medium text-gray-700">Password</label>
            <input
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                name="password"
                className="mt-1 block w-full px-3 py2 border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none"
                placeholder="Enter your password"
            />
        </div>
        <button type="submit" className="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition" >Login</button>
      </form>
        </div>
    </>
  )
}