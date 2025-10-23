import { useNativeEvent } from '@/hooks/use-native-event';
import { DownloadProgress, UpdateDownloaded, UpdateInfo } from '@/types';
import { router } from '@inertiajs/react';
import { AlertCircle, CheckCircle, Download, Loader2 } from 'lucide-react';
import { useState } from 'react';
import { Alert, AlertDescription } from './ui/alert';
import { Badge } from './ui/badge';
import { Button } from './ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from './ui/card';
import { Progress } from './ui/progress';

type UpdateStatus = 'idle' | 'checking' | 'available' | 'not-available' | 'downloading' | 'downloaded' | 'error';

function AppUpdater() {
    const [updateStatus, setUpdateStatus] = useState<UpdateStatus>('idle');
    const [updateInfo, setUpdateInfo] = useState<UpdateInfo | null>(null);
    const [downloadProgress, setDownloadProgress] = useState<DownloadProgress | null>(null);
    const [downloadedUpdate, setDownloadedUpdate] = useState<UpdateDownloaded | null>(null);
    const [error, setError] = useState<string | null>(null);
    const [isProcessing, setIsProcessing] = useState(false);

    // Use Echo hook to listen to auto-updater events
    useNativeEvent('Native\\Laravel\\Events\\AutoUpdater\\CheckingForUpdate', () => {
        setUpdateStatus('checking');
        setError(null);
    });

    useNativeEvent<UpdateInfo>('Native\\Laravel\\Events\\AutoUpdater\\UpdateAvailable', (data) => {
        setUpdateStatus('available');
        setUpdateInfo(data);
    });

    useNativeEvent<UpdateInfo>('Native\\Laravel\\Events\\AutoUpdater\\UpdateNotAvailable', (data) => {
        setUpdateStatus('not-available');
        setUpdateInfo(data);
    });

    useNativeEvent<DownloadProgress>('Native\\Laravel\\Events\\AutoUpdater\\DownloadProgress', (data) => {
        setUpdateStatus('downloading');
        setDownloadProgress(data);
    });

    useNativeEvent<UpdateDownloaded>('Native\\Laravel\\Events\\AutoUpdater\\UpdateDownloaded', (data) => {
        setUpdateStatus('downloaded');
        setDownloadedUpdate(data);
        setDownloadProgress(null);
    });

    useNativeEvent<{ error: string }>('Native\\Laravel\\Events\\AutoUpdater\\Error', (data) => {
        console.log(data);
        setUpdateStatus('error');
        setError(data.error);
    });

    const handleCheckForUpdates = () => {
        setIsProcessing(true);
        router.post(
            route('settings.update.check'),
            {},
            {
                onFinish: () => setIsProcessing(false),
                onError: (errors) => {
                    setError(Object.values(errors).join(', '));
                    setUpdateStatus('error');
                },
            },
        );
    };

    const handleInstallUpdate = () => {
        setIsProcessing(true);
        router.post(
            route('settings.update.install'),
            {},
            {
                onFinish: () => setIsProcessing(false),
                onError: (errors) => {
                    setError(Object.values(errors).join(', '));
                    setUpdateStatus('error');
                },
            },
        );
    };

    const formatBytes = (bytes: number): string => {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    };

    const formatSpeed = (bytesPerSecond: number): string => {
        return formatBytes(bytesPerSecond) + '/s';
    };

    const getBadgeContent = () => {
        switch (updateStatus) {
            case 'checking':
                return (
                    <Badge variant="secondary">
                        <Loader2 className="mr-1 h-3 w-3 animate-spin" />
                        Checking...
                    </Badge>
                );
            case 'available':
                return (
                    <Badge variant="destructive">
                        <Download className="mr-1 h-3 w-3" />
                        Update Available
                    </Badge>
                );
            case 'not-available':
                return (
                    <Badge variant="default">
                        <CheckCircle className="mr-1 h-3 w-3" />
                        Up to date
                    </Badge>
                );
            case 'downloading':
                return (
                    <Badge variant="secondary">
                        <Download className="mr-1 h-3 w-3" />
                        Downloading...
                    </Badge>
                );
            case 'downloaded':
                return (
                    <Badge variant="secondary">
                        <CheckCircle className="mr-1 h-3 w-3" />
                        Ready to Install
                    </Badge>
                );
            case 'error':
                return (
                    <Badge variant="destructive">
                        <AlertCircle className="mr-1 h-3 w-3" />
                        Error
                    </Badge>
                );
            default:
                return (
                    <Badge variant="default">
                        <CheckCircle className="mr-1 h-3 w-3" />
                        Up to date
                    </Badge>
                );
        }
    };

    const getCurrentUpdateData = () => {
        if (downloadedUpdate) return downloadedUpdate;
        if (updateInfo) return updateInfo;
        return null;
    };

    const currentUpdate = getCurrentUpdateData();
    const isButtonDisabled = isProcessing || updateStatus === 'checking' || updateStatus === 'downloading';

    return (
        <Card>
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <div>
                    <CardTitle>App Updates</CardTitle>
                    <CardDescription>Keep your Printio application up to date</CardDescription>
                </div>
                <div>{getBadgeContent()}</div>
            </CardHeader>
            <CardContent className="space-y-4">
                {/* Error Alert */}
                {error && (
                    <Alert variant="destructive">
                        <AlertCircle className="h-4 w-4" />
                        <AlertDescription>{error}</AlertDescription>
                    </Alert>
                )}

                {/* Download Progress */}
                {updateStatus === 'downloading' && downloadProgress && (
                    <div className="space-y-2">
                        <div className="flex justify-between text-sm">
                            <span>Downloading update...</span>
                            <span>{downloadProgress.percent.toFixed(1)}%</span>
                        </div>
                        <Progress value={downloadProgress.percent} className="w-full" />
                        <div className="flex justify-between text-xs text-muted-foreground">
                            <span>
                                {formatBytes(downloadProgress.transferred)} / {formatBytes(downloadProgress.total)}
                            </span>
                            <span>{formatSpeed(downloadProgress.bytesPerSecond)}</span>
                        </div>
                    </div>
                )}

                {/* Update Information */}
                {currentUpdate && (
                    <div className="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span className="font-medium text-muted-foreground">Version</span>
                            <div className="font-mono">{currentUpdate.version}</div>
                        </div>
                        <div>
                            <span className="font-medium text-muted-foreground">Release Date</span>
                            <div>{new Date(currentUpdate.releaseDate).toLocaleDateString()}</div>
                        </div>
                        {currentUpdate.releaseName && (
                            <div className="col-span-2">
                                <span className="font-medium text-muted-foreground">Release Name</span>
                                <div>{currentUpdate.releaseName}</div>
                            </div>
                        )}
                    </div>
                )}

                {/* Action Buttons */}
                <div className="flex gap-2">
                    <Button variant="outline" onClick={handleCheckForUpdates} disabled={isButtonDisabled}>
                        {updateStatus === 'checking' || isProcessing ? (
                            <>
                                <Loader2 className="mr-2 h-4 w-4 animate-spin" />
                                Checking...
                            </>
                        ) : (
                            'Check for updates'
                        )}
                    </Button>

                    {updateStatus === 'available' && (
                        <Button onClick={handleInstallUpdate} disabled={isButtonDisabled}>
                            <Download className="mr-2 h-4 w-4" />
                            Download Update
                        </Button>
                    )}

                    {updateStatus === 'downloaded' && (
                        <Button onClick={handleInstallUpdate} disabled={isButtonDisabled}>
                            <CheckCircle className="mr-2 h-4 w-4" />
                            Install & Restart
                        </Button>
                    )}
                </div>

                {/* Additional Info */}
                {updateStatus === 'not-available' && (
                    <p className="text-sm text-muted-foreground">Your application is up to date. Last checked: {new Date().toLocaleString()}</p>
                )}
            </CardContent>
        </Card>
    );
}

export default AppUpdater;
