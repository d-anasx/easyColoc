<tr class="border-b border-gray-200 hover:bg-gray-50 transition">
    <td class="px-6 py-4">
        <div>
            <p class="text-sm font-medium text-gray-900">{{ $expense->title }}</p>
            <p class="text-xs text-gray-500">{{ $expense->category }}</p>
        </div>
    </td>
    <td class="px-6 py-4">
        <p class="text-sm text-gray-600">{{ number_format($expense->amount, 2, ',', '.') }}€</p>
    </td>
    <td class="px-6 py-4">
        <p class="text-sm text-gray-600">{{ $expense->paidBy->name }}</p>
    </td>
    <td class="px-6 py-4">
        <p class="text-sm text-gray-500">{{ $expense->date->format('d/m/Y') }}</p>
    </td>
    <td class="px-6 py-4 text-right">
        <div class="flex gap-2 justify-end">
            <a href="{{ route('expenses.edit', $expense) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                Modifier
            </a>
            <form method="POST" action="{{ route('expenses.destroy', $expense) }}" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm('Êtes-vous sûr?')">
                    Supprimer
                </button>
            </form>
        </div>
    </td>
</tr>
