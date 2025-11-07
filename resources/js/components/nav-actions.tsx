import AppearanceMode from '@/components/appearance-mode';
import { Button } from '@/components/ui/button';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import {
    Sidebar,
    SidebarContent,
    SidebarGroup,
    SidebarGroupContent,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useInitials } from '@/hooks/use-initials';
import { logout } from '@/routes';
import { edit } from '@/routes/profile';
import { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { LogOut, Settings2 } from 'lucide-react';
import { useState } from 'react';

export function NavActions() {
    const { auth } = usePage<SharedData>().props;
    const [isOpen, setIsOpen] = useState(false);
    const getInitials = useInitials();
    const name =
        auth.user.profile.first_name + ' ' + auth.user.profile.last_name;

    return (
        <div className="flex items-center gap-2 text-sm">
            <AppearanceMode />
            <Popover open={isOpen} onOpenChange={setIsOpen}>
                <PopoverTrigger asChild>
                    <Button
                        variant="secondary"
                        size="icon"
                        className="h-8 w-8 rounded-full bg-gray-100 p-5 hover:bg-gray-200 data-[state=open]:bg-accent dark:bg-accent hover:dark:bg-neutral-700"
                    >
                        {getInitials(name)}
                    </Button>
                </PopoverTrigger>
                <PopoverContent
                    className="w-56 overflow-hidden rounded-lg p-0"
                    align="end"
                >
                    <Sidebar collapsible="none" className="bg-transparent">
                        <SidebarContent>
                            <SidebarGroup className="border-b last:border-none">
                                <div className="mb-2 flex items-center gap-1">
                                    <div className="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 font-semibold dark:bg-accent">
                                        {getInitials(name)}
                                    </div>
                                    <div className="mb-1 ml-2 flex flex-col">
                                        <span className="text-sm font-bold">
                                            {name}
                                        </span>
                                        <span className="text-xs text-muted-foreground">
                                            {auth.user.email}
                                        </span>
                                    </div>
                                </div>

                                <SidebarGroupContent className="gap-0">
                                    <SidebarMenu>
                                        <SidebarMenuItem>
                                            <Link
                                                className="w-full"
                                                href={edit()}
                                            >
                                                <SidebarMenuButton>
                                                    <Settings2 className="text-muted-foreground" />
                                                    <span>Settings</span>
                                                </SidebarMenuButton>
                                            </Link>
                                        </SidebarMenuItem>
                                        <SidebarMenuItem>
                                            <Link
                                                className="w-full"
                                                method="post"
                                                href={logout()}
                                                as="button"
                                            >
                                                <SidebarMenuButton>
                                                    <LogOut className="text-muted-foreground" />
                                                    <span>Logout</span>
                                                </SidebarMenuButton>
                                            </Link>
                                        </SidebarMenuItem>
                                    </SidebarMenu>
                                </SidebarGroupContent>
                            </SidebarGroup>
                        </SidebarContent>
                    </Sidebar>
                </PopoverContent>
            </Popover>
        </div>
    );
}
