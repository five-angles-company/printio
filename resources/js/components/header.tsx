import { Wifi, WifiOff } from 'lucide-react';
import { useState } from 'react';

function Header() {
    const [isConnected, setIsConnected] = useState(true);

    return (
        <header className="border-b border-slate-200 bg-white px-6 py-4">
            <div className="flex items-center justify-between">
                <div className="flex items-center space-x-3">
                    <div>
                        <h1 className="text-xl font-semibold text-slate-900">Printer Bridge</h1>
                        <p className="text-sm text-slate-500">Pharmacy Print Management</p>
                    </div>
                </div>

                <div className="flex items-center space-x-3">
                    <div
                        className={`flex items-center space-x-2 rounded-full px-3 py-1.5 text-sm font-medium ${
                            isConnected ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'
                        }`}
                        onClick={() => setIsConnected(!isConnected)}
                    >
                        {isConnected ? <Wifi className="h-4 w-4" /> : <WifiOff className="h-4 w-4" />}
                        <span>{isConnected ? 'Connected' : 'Disconnected'}</span>
                    </div>
                </div>
            </div>
        </header>
    );
}

export default Header;
