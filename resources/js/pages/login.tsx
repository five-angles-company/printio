import LoginForm from '@/components/forms/login-form';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Link } from '@inertiajs/react';

function Login() {
    return (
        <div className="flex min-h-screen items-center justify-center bg-gray-50 p-6 dark:bg-gray-900">
            <div className="w-full max-w-md space-y-4">
                {/* Back button */}
                <div className="flex justify-start">
                    <Button variant="outline" size="sm">
                        <Link href={route('dashboard.index')}>← Back to Dashboard</Link>
                    </Button>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>Login to your account</CardTitle>
                        <CardDescription>Enter your email below to login to your account</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <LoginForm />
                    </CardContent>
                </Card>
            </div>
        </div>
    );
}

export default Login;
