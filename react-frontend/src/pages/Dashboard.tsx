import { Link } from "react-router-dom";
import LogoutButton from "../components/LogoutButton";

export default function Dashboard() {

    const user = JSON.parse(localStorage.getItem("user") || "{}");

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
                        <LogoutButton />
                    </div>
                </aside>

                {/* Main content */}
                <div className="flex-1 flex flex-col">

                    {/* Navbar */}
                    <header className="bg-white shadow px-6 py-4 flex items-center justify-between">
                        <h1 className="text-xl font-semibold text-gray-800">
                            Dashboard
                        </h1>

                        <div className="flex items-center space-x-4">
                            <span className="text-gray-600">{ user.name }</span>
                            <div className="w-8 h-8 rounded-full bg-gray-300" />
                        </div>
                    </header>

                    {/* Page content */}
                    <main className="p-6">
                        {/* Stats */}
                        <section className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div className="bg-white p-6 rounded shadow">
                                <h3 className="text-sm text-gray-500">Users</h3>
                                <p className="text-2xl font-bold text-gray-800">1,245</p>
                            </div>

                            <div className="bg-white p-6 rounded shadow">
                                <h3 className="text-sm text-gray-500">Orders</h3>
                                <p className="text-2xl font-bold text-gray-800">320</p>
                            </div>

                            <div className="bg-white p-6 rounded shadow">
                                <h3 className="text-sm text-gray-500">Revenue</h3>
                                <p className="text-2xl font-bold text-gray-800">$8,430</p>
                            </div>
                        </section>

                        {/* Activity */}
                        <section className="bg-white p-6 rounded shadow">
                            <h2 className="text-xl font-semibold text-gray-800 mb-4">
                                Recent activity
                            </h2>

                            <ul className="space-y-3 text-gray-700">
                                <li>✅ User registered</li>
                                <li>🔐 User logged in</li>
                                <li>📝 Profile updated</li>
                            </ul>
                        </section>
                    </main>
                </div>
            </div>
        </>
    )
}