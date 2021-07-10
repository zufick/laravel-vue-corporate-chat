export default function auth({ to, next, store }) {
    const loginQuery = { path: "/login", query: { redirect: to.fullPath } };

    if (!store.user.id) {
        next(loginQuery);
    } else {
        next();
    }
}
