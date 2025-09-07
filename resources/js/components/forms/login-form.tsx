import { useForm } from '@inertiajs/react';
import { Button } from '../ui/button';
import { Input } from '../ui/input';
import { Label } from '../ui/label';

function LoginForm() {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('auth.login'));
    };
    return (
        <form onSubmit={submit}>
            <div className="flex flex-col gap-6">
                <div className="grid gap-3">
                    <Label htmlFor="email">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        placeholder="m@example.com"
                        required
                    />
                    {errors.email && <p className="text-sm text-red-500">{errors.email}</p>}
                </div>
                <div className="grid gap-3">
                    <Label htmlFor="password">Password</Label>
                    <Input id="password" type="password" value={data.password} onChange={(e) => setData('password', e.target.value)} required />
                    {errors.password && <p className="text-sm text-red-500">{errors.password}</p>}
                </div>
                <div className="flex flex-col gap-3">
                    <Button type="submit" className="w-full" disabled={processing}>
                        {processing ? 'Logging in...' : 'Login'}
                    </Button>
                </div>
            </div>
        </form>
    );
}

export default LoginForm;
