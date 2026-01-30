
import { useState } from "react";
import { profile } from "../services/authService";
import { Link, useNavigate } from "react-router-dom";

export default function Profile() {

    const user = JSON.parse(localStorage.getItem("user") || "{}");

    const [name, setName] = useState(user.name || "");
    const [email, setEmail] = useState(user.email || "");
    const [password, setPassword] = useState("");
    const [confirmedPassword, setConfirmedPassword] = useState("");
    const [errors, setErrors] = useState([]);
    const navigate = useNavigate();

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        setErrors([]);
        profile({ name: name, email: email, password: password, password_confirmation: confirmedPassword }, user.token).then(res => {
            if (res.data.errors) {
                setErrors(res.data.errors);
            } else {
                localStorage.setItem("user", JSON.stringify({
                    ...user,
                    name: res.data.name,
                    email: res.data.email
                }));
                navigate("/dashboard");
            }
        });
    }

    return (
        <>
            <div className="min-h-screen flex bg-gray-100">

                {/* Aside / Sidebar */}
                <aside className="w-64 bg-gray-900 text-white flex flex-col">
                    <div className="p-6 text-xl font-bold border-b border-gray-700">
                        MyApp
                    </div>

                    <nav className="flex-1 p-4 space-y-2">
                        <Link to="/dashboard" className="block px-4 py-2 rounded hover:bg-gray-800">
                            Dashboard
                        </Link>
                        <Link to="/profile" className="block px-4 py-2 rounded hover:bg-gray-800">
                            Profile
                        </Link>
                    </nav>

                    <div className="p-4 border-t border-gray-700">
                        <button className="w-full bg-red-600 py-2 rounded hover:bg-red-700 transition">
                            Logout
                        </button>
                    </div>
                </aside>

                {/* Main content */}
                <div className="flex-1 flex flex-col">

                    {/* Navbar */}
                    <header className="bg-white shadow px-6 py-4 flex items-center justify-between">
                        <h1 className="text-xl font-semibold text-gray-800">
                            Profile
                        </h1>

                        <div className="flex items-center space-x-4">
                            <span className="text-gray-600">{user.name}</span>
                            <div className="w-8 h-8 rounded-full bg-gray-300" />
                        </div>
                    </header>

                    {/* Page content */}
                    <main className="p-6">

                        {/* Update profile form */}
                        <section className="bg-white p-6 rounded shadow">
                            <h2 className="text-xl font-semibold text-gray-800 mb-4">
                                Update your profile
                            </h2>
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
                                    <label className="block text-sm font-medium text-gray-700">Name</label>
                                    <input
                                        type="text"
                                        value={name}
                                        onChange={(e) => setName(e.target.value)}
                                        name="name"
                                        className="mt-1 block w-full px-3 py2 border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none"
                                        placeholder="Enter your name"
                                    />
                                </div>

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

                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Confirm Password</label>
                                    <input
                                        type="password"
                                        value={confirmedPassword}
                                        onChange={(e) => setConfirmedPassword(e.target.value)}
                                        name="password_confirmation"
                                        className="mt-1 block w-full px-3 py2 border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none"
                                        placeholder="Enter your password again"
                                    />
                                </div>

                                <button type="submit" className="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition" >Register</button>
                            </form>
                        </section>
                    </main>
                </div>
            </div>
        </>
    )
}