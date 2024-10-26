<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.1/tailwind.min.css">
</head>
<body>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Documents</h1>
        @if ($documents->isEmpty())
            <p>No documents found.</p>
        @else
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Title</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Hash</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documents as $document)
                        <tr>
                            <td class="border px-4 py-2">{{ $document->id }}</td>
                            <td class="border px-4 py-2">{{ $document->title }}</td>
                            <td class="border px-4 py-2">{{ $document->Doc_desc }}</td>
                            <td class="border px-4 py-2">{{ $document->hash }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>
