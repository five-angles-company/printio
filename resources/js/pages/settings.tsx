import MainLayout from '@/layouts/main-layout';

function Settings() {
    return <div>Settings</div>;
}

export default Settings;

Settings.layout = (page: React.ReactNode) => <MainLayout children={page} />;
